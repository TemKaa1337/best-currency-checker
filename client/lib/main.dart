import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

void main() => runApp(const App());

class App extends StatelessWidget {
  const App({Key? key}) : super(key: key);

  @override
  Widget build (BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primaryColor: Colors.blue
      ),
      home: Scaffold(
        appBar: AppBar(
          title: const Text('test')
        ),
        body: const Departments()
      )
    );
  }
}

class Departments extends StatefulWidget {
  const Departments({Key? key}) : super(key: key);

  @override
  _DepartmentsState createState() => _DepartmentsState();
}

class _DepartmentsState extends State<Departments> {
  Future<http.Response> getDepartmentInfo() async {
    var response = await http.post(Uri.https('best-currency-rates.temkaatrashprojects.tech', '/api/get/nearest/departments'));
    
    if (response.statusCode == 200) {
      var result = jsonDecode(response.body);
      List<Department> departments = [];

      for (var department in result) {
        Department departmentInfo = Department(department['name'], department['address'], department['website']);
        departments.add(departmentInfo);
      }
    } else {

    }

    return response;
  }

  @override
  Widget build (BuildContext context) {
    return const Scaffold(

    );
  }
}

class Department {
  final String name, address, website;
  Department(this.name, this.address, this.website);
}
