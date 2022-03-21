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
            Padding(
              padding: EdgeInsets.only(top: 20),
              child: SizedBox(
                child: Text('К сожалению мы не нашли ни одного отделения поблизости, попробуйте увеличить радиус поиска.', textAlign: TextAlign.center,),
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