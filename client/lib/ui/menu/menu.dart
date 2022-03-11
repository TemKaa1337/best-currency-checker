import 'package:flutter/material.dart';

class MenuWidget extends StatefulWidget {
  const MenuWidget({Key? key}) : super(key: key);

  @override
  _MenuWidgetState createState() => _MenuWidgetState();
}

class _MenuWidgetState extends State<MenuWidget> {
  @override
  Widget build (BuildContext context) {
    return Drawer(
      child: ListView(
        children: [
          const SizedBox(
              child: DrawerHeader(
                  decoration: BoxDecoration(
                      color: Colors.blue
                  ),
                  child: Text('Выберите валюту:')
              ),
              height: 50
          ),
          Card(
            shape: RoundedRectangleBorder(
              side: BorderSide(
                color: Colors.green.shade300,
              ),
              borderRadius: BorderRadius.circular(15.0),
            ),
            child: ListTile(
                title: const Text('USD'),
                onTap: () {

                }
            ),
          ),
          Card(
            shape: RoundedRectangleBorder(
              side: BorderSide(
                color: Colors.green.shade300,
              ),
              borderRadius: BorderRadius.circular(15.0),
            ),
            child: ListTile(
                title: const Text('EUR'),
                onTap: () {

                }
            ),
          ),
        ],
      ),
    );
  }
}