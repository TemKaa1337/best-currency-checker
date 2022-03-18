import 'package:flutter/material.dart';

class DepartmentEmpty extends StatelessWidget {
  const DepartmentEmpty({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: const <Widget> [
              SizedBox(
                child: Text('Sad but we didnt find any departments near toy, please set other parameters bigger, like radius.'),
                height: 50,
                width: 250,
              ),
            ],
          ),
        ]
    );
  }
}