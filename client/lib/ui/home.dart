import 'dart:convert';
import 'package:client/services/data/department.dart';
import 'package:flutter/material.dart';
import 'package:client/ui/buttons/currency_type_switcher.dart';
import 'package:client/ui/buttons/currency_operation_switcher.dart';
import 'package:client/ui/body/department_list.dart';
import 'package:client/services/enums/department_state.dart';
import 'package:http/http.dart' as http;

class Home extends StatefulWidget {
  const Home({Key? key}) : super(key: key);

  @override
  _HomeState createState() => _HomeState();
}

class _HomeState extends State<Home> {
  DepartmentState _requestState = DepartmentState.loading;
  late String _errorMessage;
  List<Department> _departments = [];

  Future getNearestDepartments() async {
    final Map<String, dynamic> params = {
      'location': '53.901780,27.551184',
      'radius': 5000
    };
    
    http.Response response = await http.post(
      Uri.https('currency-checker.temkaatrashprojects.tech', '/api/get/nearest/departments'),
      headers: <String, String> {
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: jsonEncode(params)
    );

    print(response.body);

    if (response.statusCode == 200) {
      List body = jsonDecode(response.body);

      setState(() {
        _errorMessage = '';
        print(body);
        _departments = body.map((dynamic element) => Department.fromJson(element)).toList();
        _requestState = DepartmentState.success;
      });
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

  @override
  void initState() {
    super.initState();
    getNearestDepartments();
  }

  @override
  Widget build(BuildContext context) {
    print('state is');
    print(_requestState);

    return Scaffold(
      appBar: AppBar(
        leadingWidth: 140,
        leading: Row(
          children: const [
            CurrencyTypeSwitcher()
          ]
        ),
        actions: <Widget> [
          Builder(
            builder: (context) {
              return const CurrencyOperationSwitcher();
            },
          )
        ],
      ),
      body: Scaffold(
        body: [DepartmentState.loading, DepartmentState.error].contains(_requestState)
          ? const Text('Not loaded yeat')
          : ListView.builder(
            itemCount: _departments.length,
            itemBuilder: (context, index) {
              return DepartmentList(
                department: _departments[index],
              );
            }
          )
      ),
      floatingActionButton: FloatingActionButton.extended(
        label: const Icon(IconData(0xf2f7, fontFamily: 'MaterialIcons')),
        backgroundColor: Colors.blue,
        onPressed: () {

        },
      ),
    );
  }
}