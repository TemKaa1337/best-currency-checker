import 'package:flutter/material.dart';

class DepartmentLoading extends StatelessWidget {
  const DepartmentLoading({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: const <Widget> [
            SizedBox(
              child: Text('Departments are loading, please wait...'),
              height: 50,
              width: 250,
            ),
          ],
        ),
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: const <Widget> [
            SizedBox(
              child: CircularProgressIndicator(),
              height: 200.0,
              width: 200.0,
            ),
          ],
        )
      ],

    );
  }
}