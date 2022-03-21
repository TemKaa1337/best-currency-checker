import 'package:flutter/material.dart';

class RadiusSwitcherCell extends StatefulWidget {
  final Function(int) notifyParent;
  final bool isEnabled;
  final int radius;

  const RadiusSwitcherCell({
    Key? key, 
    required final Function(int) this.notifyParent,
    required final bool this.isEnabled,
    required final int this.radius
  }) : super(key: key);

  @override
  _RadiusSwitcherCellState createState() => _RadiusSwitcherCellState();
}

class _RadiusSwitcherCellState extends State<RadiusSwitcherCell> {
  void notifyParent() {
    widget.notifyParent(widget.radius);
  }

  @override
  Widget build(BuildContext context) {
    return ToggleButtons(
      isSelected: [widget.isEnabled],
      children: [Text('${widget.radius / 1000}км')],
      borderRadius: const BorderRadius.all(Radius.circular(8)),
      borderWidth: 1,
      selectedColor: Colors.black,
      fillColor: Colors.blue,
      color: Colors.black,
      onPressed: (int index) {
        if (!widget.isEnabled) {
          notifyParent();
        }
      }
    );
  }
}