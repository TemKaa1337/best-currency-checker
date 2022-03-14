class Department {
  final int id, distance;
  final String departmentName, address, website, workingTime, bankName, lastUpdate;
  final List<String> phones, coordinates;
  final Map<String, Map<String, dynamic>> currencyRates;

  Department(
    this.id, 
    this.departmentName,
    this.address, 
    this.website,
    this.phones,
    this.workingTime,
    this.currencyRates,
    this.coordinates,
    this.bankName,
    this.lastUpdate,
    this.distance
  );

  // TODO: add coordinates, bank_name, last_update, distance
  Department.fromJson(Map<String, dynamic> json)
      :
        id = json['id'],
        departmentName = json['name'],
        address = json['address'],
        website = json['website'],
        phones = json['phones'].cast<String>(),
        workingTime = json['working_time'],
        currencyRates = Map<String, Map<String, dynamic>>.from(json['currency_info']),
        coordinates = json['coordinates'].cast<String>(),
        bankName = json['bank_name'],
        lastUpdate = json['last_update'],
        distance = json['distance'];
}