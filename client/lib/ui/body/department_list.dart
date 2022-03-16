import 'package:flutter/material.dart';
import 'package:client/services/data/department.dart';

class DepartmentList extends StatefulWidget {
  final Department department;
  final String currency;
  final String operationType;

  const DepartmentList({
    Key? key,
    required Department this.department,
    required String this.currency,
    required String this.operationType
  }) : super(key: key);

  @override
  _DepartmentListState createState() => _DepartmentListState();
}

class _DepartmentListState extends State<DepartmentList> with AutomaticKeepAliveClientMixin<DepartmentList> {
  @override
  bool get wantKeepAlive => true;

  @override
  Widget build (BuildContext context) {
    return ExpansionTile(
      title: Row(
        children: [
          Text(widget.department.bankName),
          Padding(
            padding: const EdgeInsets.all(3),
            child: Text(
              'Курс: ${widget.department.currencyRates[widget.currency]![widget.operationType]!}',
              style: const TextStyle(color: Colors.blue),
            ),
          )
        ],
      ),
      subtitle: Text(widget.department.address + ' (в ${widget.department.distance.toString()} метрах от вас)'),
      key: PageStorageKey<int>(widget.department.id),
      children: [
        ListTile(
          title: Text(widget.department.website),
          subtitle: Text(widget.department.phones.join('\n'))
        ),
        ListTile(
            title: Text(widget.department.workingTime),
            subtitle: Text('Последнее обновление курса: ' + widget.department.lastUpdate)
        )
      ],
    );
  }
}