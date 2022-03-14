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
  List<Department> departments = [];

  Future getNearestDepartments() async {
    http.Response response = await http.post(Uri.https('currency-checker.temkaatrashprojects.tech', '/api/get/nearest/departments'));

    if (response.statusCode == 200) {
      setState(() {
        _errorMessage = '';
      });
    } else {
      if (_requestState != DepartmentState.success) {
        // settings this state only if previous request was unsuccessful
        setState(() {
          _requestState = DepartmentState.error;
          print(response.body);
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
      body:
          // [DepartmentState.loading, DepartmentState.error].contains(_requestState)
          // ? AlertDialog(
          //   title: const Text('title'),
          //   actions: [
          //     TextButton(
          //       onPressed: () {
          //         // Navigator.pop(context);
          //       },
          //       child: const Text('OK')
          //     )
          //   ],
          //   content: const Text('content')
          // ) :
          const DepartmentList(),
      floatingActionButton: FloatingActionButton.extended(
        label: const Icon(IconData(0xf2f7, fontFamily: 'MaterialIcons')),
        backgroundColor: Colors.blue,
        onPressed: () {

        },
      ),
    );
  }
}