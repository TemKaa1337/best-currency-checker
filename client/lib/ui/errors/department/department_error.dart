import 'package:flutter/material.dart';

class DepartmentError extends StatelessWidget {
  final String errorMessages;

  const DepartmentError({Key? key, required String this.errorMessages}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: const <Widget> [
            Padding(
              padding: EdgeInsets.only(top: 20),
              child:  SizedBox(
                child: Text('The is an error trying to fetch departments. Try again later.'),
                height: 50,
                width: 300,
              ),
            )
          ],
        ),
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: <Widget> [
            Padding(
              padding: const EdgeInsets.only(top: 20),
              child:  SizedBox(
                child: Text('Errors: $errorMessages'),
                height: 50,
                width: 300,
              ),
            )
          ],
        ),
      ]
    );
  }
}