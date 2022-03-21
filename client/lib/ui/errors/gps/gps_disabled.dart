import 'package:flutter/material.dart';

class GpsDisabled extends StatelessWidget {
  const GpsDisabled({Key? key}) : super(key: key);

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
                child: Text('Your GPS is disabled, please enable it.'),
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