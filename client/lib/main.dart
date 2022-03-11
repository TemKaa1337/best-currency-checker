import 'package:client/ui/menu/menu.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:client/ui/buttons/currency_type_switcher.dart';
import 'package:client/ui/buttons/currency_operation_switcher.dart';
import 'package:client/services/data/department.dart';
import 'package:client/services/data/menu.dart';

void main() => runApp(const App());

class App extends StatelessWidget {
  const App({Key? key}) : super(key: key);

  @override
  Widget build (BuildContext context) {
    List<Menu> menuOptions = [
      Menu('USD'),
      Menu('EUR')
    ];

    return MaterialApp(
      theme: ThemeData(
        primaryColor: Colors.blue
      ),
      home: Scaffold(
        // drawer: const MenuWidget(),
        appBar: AppBar(
          // title: const Text('Current : usd?'),
          actions: [
            Padding(
              padding: const EdgeInsets.only(left: 20.0),
              child: Row(
                children: const [
                  CurrencyTypeSwitcher(),
                ],
              )
            ),
            Padding(
                padding: const EdgeInsets.only(right: 20.0),
                child: Row(
                  children: const [
                    CurrencyOperationSwitcher(),
                  ],
                )
            ),
          ],
        ),
        body: const Departments(),
        floatingActionButton: FloatingActionButton.extended(
          label: const Text(''),
          backgroundColor: Colors.blue,
          icon: const Icon(IconData(0xf2f7, fontFamily: 'MaterialIcons')),
          onPressed: () {

          },
        ),
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
    return response;
    // var result = jsonDecode(response.body);
    // List<Department> departments = [];
    //
    // for (var department in result) {
    //   Department departmentInfo = Department(department['name'], department['address'], department['website']);
    //   departments.add(departmentInfo);
    // }
    //
    // return departments;
  }

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
