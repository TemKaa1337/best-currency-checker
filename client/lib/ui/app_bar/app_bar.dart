import 'package:flutter/material.dart';

class DepartmentsAppBar extends StatelessWidget with PreferredSizeWidget {
  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      bottom: const TabBar(
        tabs: [
          SizedBox(
              width: 30,
              height: 35,
              child: Icon(Icons.currency_exchange_outlined)
          ),
          SizedBox(
              width: 30,
              height: 35,
              child: Icon(Icons.settings)
          )
        ],
      ),
      backgroundColor: Colors.blue,
    );
  }
}