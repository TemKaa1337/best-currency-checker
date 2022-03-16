import 'package:flutter/material.dart';

class DepartmentCountSwitcher extends StatefulWidget {
  final Function(int) notifyParent;

  const DepartmentCountSwitcher({Key? key, required final Function(int) this.notifyParent}) : super(key: key);

  @override
  _DepartmentCountSwitcherState createState() => _DepartmentCountSwitcherState();
}

class _DepartmentCountSwitcherState extends State<DepartmentCountSwitcher> {
  final List<bool> _departmentSelections = List.generate(3, (index) => index == 0 ? true : false);
  final List<int> _departmentsNumber = [5, 10, 15];

  int _departmentNumberEnabled = 0;

  void notifyParent() {
    widget.notifyParent(_departmentsNumber[_departmentNumberEnabled]);
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        const Padding(
            padding: EdgeInsets.all(10),
            child: Text('Number of departments?')
        ),
        Padding(
          padding: const EdgeInsets.all(10),
          child: ToggleButtons(
              children: _departmentsNumber.map((int element) => Text((element).toString())).toList(),
              onPressed: (int index) {
                setState(() {
                  for (int i = 0; i < _departmentSelections.length; i ++) {
                    if (i == index) {
                      if (_departmentSelections[i] == false) {
                        _departmentSelections[i] = !_departmentSelections[i];
                        _departmentNumberEnabled = i;
                      }
                    } else {
                      _departmentSelections[i] = false;
                    }
                  }

                  print(_departmentSelections);
                });

                notifyParent();
              },
              isSelected: _departmentSelections,
              borderRadius: BorderRadius.circular(10),
              borderWidth: 2,
              borderColor: Colors.white,
              selectedColor: Colors.black
          ),
        )
      ],
    );
  }
}