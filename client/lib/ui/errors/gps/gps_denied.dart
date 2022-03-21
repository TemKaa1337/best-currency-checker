import 'package:flutter/material.dart';

class GpsDenied extends StatelessWidget {
  const GpsDenied({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: const <Widget> [
            Padding(
              padding: EdgeInsets.only(top: 20),
              child: SizedBox(
                child: Text('You denied access to your GPS, please allow us to use your GPS and come back!'),
                height: 50,
                width: 250,
              ),
            )
          ],
        ),
      ]
    );
  }
}