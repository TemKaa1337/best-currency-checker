import 'package:client/ui/buttons/apply_settings_button.dart';
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

class _SettingsState extends State<Settings> with AutomaticKeepAliveClientMixin<Settings> {
  String _operation = 'Buy';
  String _currency = 'USD';

  int _departmentCount = 5;
  int _radius = 500;

  @override
  bool get wantKeepAlive => true;

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
  }

  void setOperation(String operation) {
    _operation = operation;
  }

  void setRadius(int radius) {
    _radius = radius;
  }

  void setDepartmentNumber(int departmentCount) {
    _departmentCount = departmentCount;
  }

  @override
  Widget build(BuildContext context) {
    return ListView(
      scrollDirection: Axis.vertical,
      shrinkWrap: true,
      children: [
        CurrencyTypeSwitcher(notifyParent: setCurrency),
        CurrencyOperationSwitcher(notifyParent: setOperation),
        DepartmentCountSwitcher(notifyParent: setDepartmentNumber),
        RadiusSwitcher(notifyParent: setRadius),
        ApplySettingsButton(notifyParent: notifyParent)
      ],
    );
  }
}