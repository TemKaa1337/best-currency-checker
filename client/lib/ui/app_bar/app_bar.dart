import 'package:flutter/material.dart';

class DepartmentsAppBar extends StatelessWidget with PreferredSizeWidget {
  final TabController tabController;

  const DepartmentsAppBar({Key? key, required TabController this.tabController}) : super(key: key);

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      bottom: TabBar(
        controller: tabController,
        tabs: const [
          SizedBox(
              width: 30,
              height: 35,
              child: Icon(Icons.currency_exchange_outlined)
          ),
          SizedBox(
              width: 30,
              height: 35,
              child: Icon(Icons.settings)
          ),
          SizedBox(
              width: 30,
              height: 35,
              child: Icon(Icons.location_city)
          )
        ],
      ),
      backgroundColor: Colors.blue,
    );
  }
}