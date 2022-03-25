import 'package:client/services/states/department_state.dart';
import 'package:client/services/states/gps_state.dart';
import 'package:client/ui/app_bar/app_bar.dart';
import 'package:client/ui/body/department_list.dart';
import 'package:client/services/data/department.dart';
import 'package:client/ui/body/settings.dart';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:http/http.dart' as http;
import 'package:geolocator/geolocator.dart';
import 'package:client/ui/errors/department/department_error.dart';
import 'package:client/ui/errors/department/department_loading.dart';
import 'package:client/ui/errors/department/department_empty.dart';
import 'package:client/ui/errors/gps/gps_denied.dart';
import 'package:client/ui/errors/gps/gps_disabled.dart';
import 'package:client/ui/errors/gps/gps_waiting.dart';
import 'package:client/ui/body/map.dart';
import 'dart:convert';

class Home extends StatefulWidget {
  const Home({Key? key}) : super(key: key);

  @override
  _HomeState createState() => _HomeState();
}

class _HomeState extends State<Home> with TickerProviderStateMixin {
  late final TabController _tabController = TabController(length: 3, vsync: this);
  bool _refreshButtonVisible = true;

  DepartmentState _requestState = DepartmentState.loading;
  List<Department> _departments = [];
  late GpsState _gpsState;
  late Position _position;

  String _currency = 'USD';
  String _operation = 'Buy';
  int _radius = 500;
  int _departmentNumber = 5;

  Future<void> getNearestDepartments() async {
    bool success = await setUserLocation();

    final Map<String, dynamic> params = {
      // 'location': _position.latitude.toString() + ',' + _position.longitude.toString(),
      'location': '53.896171, 27.543516',
      'radius': _radius,
      'limit': _departmentNumber,
      'currency': _currency.toLowerCase(),
      'operationType': _operation == 'Buy' ? 'bank_sells' : 'bank_buys'
    };

    http.Response response = await http.post(
      Uri.https('currency-checker.temkaatrashprojects.tech', '/api/get/nearest/departments'),
      headers: <String, String> {
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: jsonEncode(params)
    );

    if (response.statusCode == 200) {
      List body = jsonDecode(response.body);

      if (body.isEmpty) {
        setState(() {
          _requestState = DepartmentState.empty;
        });
      } else {
        setState(() {
          _departments = body.map((dynamic element) => Department.fromJson(element)).toList();
          _requestState = DepartmentState.success;
        });
      }
    } else {
      if (_requestState != DepartmentState.success) {
        setState(() {
          _requestState = DepartmentState.error;
        });
      }
    }
  }

  Future<bool> setUserLocation () async {
    setState(() {
      _gpsState = GpsState.waitingForPermission;
    });

    bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      setState(() {
        _gpsState = GpsState.gpsDisabled;
      });

      return false;
    }

    LocationPermission permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        setState(() {
          _gpsState = GpsState.permissionDenied;
        });

        return false;
      }
    }

    Position position = await Geolocator.getCurrentPosition();
    setState(() {
      _gpsState = GpsState.permissionGranted;
      _position = position;
    });

    return true;
  }

  @override
  void initState() {
    super.initState();
    _tabController.addListener(() {
      if (!_tabController.indexIsChanging) {
        if ([0, 1].contains(_tabController.index)) {
          setState(() {
            _refreshButtonVisible = true;
          });
        } else {
          setState(() {
            _refreshButtonVisible = false;
          });
        }
      }
    });
    getNearestDepartments();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  void refresh(String currency, String operation, int radius, int departmentNumber) {
    _currency = currency;
    _operation = operation;
    _radius = radius;
    _departmentNumber = departmentNumber;

    _tabController.animateTo(0);
    _tabController.index = 0;

    _refreshButtonVisible = true;

    getNearestDepartments();
  }

  Widget getCurrentDepartmentWidget() {
    if (_gpsState == GpsState.gpsDisabled) {
      return const GpsDisabled();
    } else if (_gpsState == GpsState.waitingForPermission) {
      return const GpsWaiting();
    } else if (_gpsState == GpsState.permissionDenied) {
      return const GpsDenied();
    } else if (_gpsState == GpsState.permissionGranted) {
      if (_requestState == DepartmentState.loading) {
        return const DepartmentLoading();
      } else if (_requestState == DepartmentState.success) {
        return ListView.builder(
          scrollDirection: Axis.vertical,
          shrinkWrap: true,
          itemCount: _departments.length,
          itemBuilder: (context, index) {
            return DepartmentList(
              department: _departments[index],
              currency: _currency.toLowerCase(),
              operationType: _operation == 'Buy' ? 'bank_sells' : 'bank_buys',
            );
          }
        );
      } else if (_requestState == DepartmentState.error) {
        return const DepartmentError();
      } else if (_requestState == DepartmentState.empty) {
        return const DepartmentEmpty();
      }
    }

    return const Text('');
  }

  Widget getCurrentMapWidget() {
    if (_gpsState == GpsState.gpsDisabled) {
      return const GpsDisabled();
    } else if (_gpsState == GpsState.waitingForPermission) {
      return const GpsWaiting();
    } else if (_gpsState == GpsState.permissionDenied) {
      return const GpsDenied();
    } else {
      if (_requestState == DepartmentState.loading) {
        return const DepartmentLoading();
      } else if (_requestState == DepartmentState.success) {
        // LatLng userPosition = LatLng(_position.latitude, _position.longitude);
        LatLng userPosition = LatLng(53.896171, 27.543516);

        List<Map<String, dynamic>> departmentsInfo = _departments.map((Department department) {
          return {
            'coordinates': department.coordinates,
            'name': department.bankName,
            'distance': department.distance,
            'currency_rate': department.currencyRates[_currency.toLowerCase()]![_operation == 'Buy' ? 'bank_sells' : 'bank_buys' ]!,
            'sign': _currency == 'USD' ? '\$' : '€'
          };
        }).toList();

        return GoogleMapTab(
            userPosition: userPosition,
            departmentsInfo: departmentsInfo
        );
      } else if (_requestState == DepartmentState.error) {
        return const DepartmentError();
      } else if (_requestState == DepartmentState.empty) {
        return const DepartmentEmpty();
      }
    }

    return Text('');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: DepartmentsAppBar(tabController: _tabController),
      body: TabBarView(
        controller: _tabController,
        physics: const BouncingScrollPhysics(),
        children: [
          getCurrentDepartmentWidget(),
          Settings(refresh: refresh),
          getCurrentMapWidget()
        ],
      ),
      floatingActionButton: Visibility(
        visible: _refreshButtonVisible,
        child: FloatingActionButton(
          onPressed: () {
            refresh(_currency, _operation, _radius, _departmentNumber);
          },
          child: const Icon(Icons.refresh)
        ),
      )
    );
  }
}