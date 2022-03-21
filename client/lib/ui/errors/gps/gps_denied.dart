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
                child: Text('Вы не разрешили доступ в вашему GPS, пожалуйста, включите его и перезайдите в приложение!', textAlign: TextAlign.center,),
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