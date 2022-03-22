import 'package:flutter/material.dart';
import 'package:client/ui/buttons/radius_switcher_cell.dart';

class RadiusSwitcher extends StatefulWidget {
  final Function(int) notifyParent;

  const RadiusSwitcher({Key? key, required final Function(int) this.notifyParent}) : super(key: key);

  @override
  _RadiusSwitcherState createState() => _RadiusSwitcherState();
}

class _RadiusSwitcherState extends State<RadiusSwitcher> {
  final List<bool> _radiusSelections = List.generate(6, (index) => index == 0 ? true : false);
  final List<int> _radiuses = [500, 1000, 1500, 2000, 5000, 10000];

  int _radiusEnabled = 0;

  void notifyParent(int radius) {
    widget.notifyParent(radius);

    _radiusEnabled = _radiuses.indexOf(radius);

    for (int i = 0; i < _radiusSelections.length; i ++) {
      if (i == _radiusEnabled) {
        _radiusSelections[i] = true;
      } else {
        _radiusSelections[i] = false;
      }
    }

    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    int counter = 0;

    return Row(
      children: [
        const Padding(
          padding: EdgeInsets.all(10),
          child: Text('Радиус поиска:')
        ),
        const Spacer(),
        Padding(
          padding: const EdgeInsets.only(right: 10),
          child: Container (
            height: 110,
            width: 160,
            margin: EdgeInsets.zero,
            child: GridView.builder(
              padding: const EdgeInsets.all(0),
              primary: false,
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 3,
                mainAxisSpacing: 3,
                crossAxisSpacing: 1
              ),
              shrinkWrap: true,
              itemCount: _radiuses.length,
              itemBuilder: (context, index) {
                return Container(
                  margin: EdgeInsets.zero,
                  padding: EdgeInsets.zero,
                  child: RadiusSwitcherCell(notifyParent: notifyParent, isEnabled: _radiusSelections[index], radius: _radiuses[index]),
                );
              },
            )
          )
        )
      ],
    );
  }
}