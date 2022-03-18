import 'package:flutter/material.dart';

class GpsWaiting extends StatelessWidget {
  const GpsWaiting({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: const <Widget> [
            SizedBox(
              child: Text('Getting your GPS, please wait...'),
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