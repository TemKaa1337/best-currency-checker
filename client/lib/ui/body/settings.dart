import 'package:client/ui/buttons/currency_operation_switcher.dart';
import 'package:client/ui/buttons/currency_type_switcher.dart';
import 'package:client/ui/buttons/department_count_switcher.dart';
import 'package:client/ui/buttons/radius_switcher.dart';
import 'package:flutter/material.dart';

class Settings extends StatefulWidget {
  final Function(String, String, int, int) refresh;

  const Settings({Key? key, required Function(String, String, int, int) this.refresh}) : super(key: key);

  @override
  _SettingsState createState() => _SettingsState();
}

class _SettingsState extends State<Settings> {
  String _operation = 'Buy';
  String _currency = 'USD';

  int _departmentCount = 5;
  int _radius = 500;

  void notifyParent() {
    widget.refresh(
      _currency,
      _operation,
      _radius,
      _departmentCount
    );
  }

  void setCurrency(String currency) {
    _currency = currency;
    notifyParent();
  }

  void setOperation(String operation) {
    _operation = operation;
    notifyParent();
  }

  void setRadius(int radius) {
    _radius = radius;
    notifyParent();
  }

  void setDepartmentNumber(int departmentCount) {
    _departmentCount = departmentCount;
    notifyParent();
  }

  @override
  Widget build(BuildContext context) {
    return ExpansionTile(
      title: const Text('Settings'),
      children: [
        CurrencyTypeSwitcher(notifyParent: setCurrency),
        CurrencyOperationSwitcher(notifyParent: setOperation),
        RadiusSwitcher(notifyParent: setRadius),
        DepartmentCountSwitcher(notifyParent: setDepartmentNumber)
      ],
    );
  }
}