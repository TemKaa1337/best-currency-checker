import 'package:flutter/material.dart';
import 'package:client/services/data/department.dart';

class DepartmentList extends StatefulWidget {
  // final List<Department> departments;
  // final String error;

  const DepartmentList({Key? key}) : super(key: key);

  @override
  _DepartmentListState createState() => _DepartmentListState();
}

class _DepartmentListState extends State<DepartmentList> {
  @override
  Widget build (BuildContext context) {
    List<Department> departments = [
      Department(1, 'name1', 'address1', 'website1'),
      Department(2, 'name2', 'address2', 'website2'),
      Department(3, 'name3', 'address3', 'website3'),
      Department(4, 'name4', 'address4', 'website4'),
      Department(5, 'name5', 'address6', 'website6'),
    ];

    return Scaffold(
      body: ListView.builder(
        itemCount: departments.length,
        itemBuilder: (context, index) {
          return ExpansionTile(
            title: Text(departments[index].name),
            key: PageStorageKey<int>(departments[index].id),
            children: [
              ListTile(
                title: Text(departments[index].name + ' asd1'),
                subtitle: Text(departments[index].address)
              ),
              ListTile(
                title: Text(departments[index].name + ' asd2'),
                subtitle: Text(departments[index].address)
              ),
            ],
          );
        }
      ),
    );
  }
}