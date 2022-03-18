import 'package:client/services/states/department_state.dart';
import 'package:client/services/states/gps_state.dart';
import 'package:client/ui/app_bar/app_bar.dart';
import 'package:client/ui/body/department_list.dart';
import 'package:client/services/data/department.dart';
import 'package:client/ui/body/settings.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:geolocator/geolocator.dart';
import 'package:client/ui/errors/department/department_error.dart';
import 'package:client/ui/errors/department/department_loading.dart';
import 'package:client/ui/errors/department/department_empty.dart';
import 'package:client/ui/errors/gps/gps_denied.dart';
import 'package:client/ui/errors/gps/gps_disabled.dart';
import 'package:client/ui/errors/gps/gps_waiting.dart';
import 'dart:convert';

class Home extends StatefulWidget {
  const Home({Key? key}) : super(key: key);

  @override
  _HomeState createState() => _HomeState();
}

class _HomeState extends State<Home> with SingleTickerProviderStateMixin {
  DepartmentState _requestState = DepartmentState.loading;
  late String _errorMessage;
  List<Department> _departments = [];
  late GpsState _gpsState;
  late Position _position;

  String _currency = 'USD';
  String _operation = 'Buy';
  int _radius = 500;
  int _departmentNumber = 5;

  Future getNearestDepartments() async {
    bool success = await setUserLocation();
    // print('after setting user location');

    final Map<String, dynamic> params = {
      // 'location': '53.901780,27.551184',
      'location': _position.latitude.toString() + ',' + _position.longitude.toString(),
      'radius': _radius,
      'limit': _departmentNumber,
      'currency': _currency.toLowerCase(),
      'operationType': _operation == 'Buy' ? 'bank_sells' : 'bank_buys'
    };
    // print('before request sent');
    http.Response response = await http.post(
      Uri.https('currency-checker.temkaatrashprojects.tech', '/api/get/nearest/departments'),
      headers: <String, String> {
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: jsonEncode(params)
    );
    // print(response.body);

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
        // settings this state only if previous request was unsuccessful
        setState(() {
          _requestState = DepartmentState.error;
          Map<String, dynamic> listResponse = jsonDecode(response.body);
          _errorMessage = listResponse['errors'].join('\n');
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

    // print('[position:]');
    // print(_position);
    // print(_position.latitude);
    // print(_position.longitude);

    return true;
  }

  @override
  void initState() {
    super.initState();
    getNearestDepartments();
  }

  void refresh(String currency, String operation, int radius, int departmentNumber) {
    _currency = currency;
    _operation = operation;
    _radius = radius;
    _departmentNumber = departmentNumber;

    getNearestDepartments();
  }

  Widget getCurrentWidget() {
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
        return DepartmentError(errorMessages: _errorMessage);
      } else if (_requestState == DepartmentState.empty) {
        return const DepartmentEmpty();
      }
    }

    return Text('pizdec');
  }

  @override
  Widget build(BuildContext context) {
    // print('state is');
    // print(_requestState);
    // print('currency');
    // print(_currency);
    // print('operation');
    // print(_operation);
    // print('radius');
    // print(_radius);
    // print('department numbers');
    // print(_departmentNumber);

    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: DepartmentsAppBar(),
        body: TabBarView(
          physics: const BouncingScrollPhysics(),
          children: [
            getCurrentWidget(),
            Settings(refresh: refresh)
          ],
        ),
        floatingActionButton: FloatingActionButton(
          onPressed: () {
            refresh(_currency, _operation, _radius, _departmentNumber);
          },
          child: const Icon(Icons.refresh)
        ),
      ),
    );
  }
}