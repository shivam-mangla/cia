police_members (police commisioner and police officer)
========

id, username, passwd, name, role belongs {p_commisioner, p_officer}, timestamps

police_stations
========

id, stationname, name, address (can be broken further), type belongs {district_lvl, city_lvl}, timestamps

police_station_and_member_map
========

id, station_id, member_id

reports
=========

id, aadhaar_no, status belongs to {open, in_process, in_review, closed}, timestamps


report_police_member_map
========

id, report_id, member_id, timestamps


records
=========

id, title, description, type belongs to {murder_fir, theft_fir, abuse_fir, murder, theft, abuse}, creator_id, timestamps


report_record_map
========

id, report_id, record_id, timestamps


attachments
==========

id, url, type belongs to {pdf, image, spreadsheet, document}

record_attachments_map
===========

id, record_id, attachment_id


report_verification_map
===========
id, report_id, verified_by, timestamps


XXXXXXXXXX_report_citizen_map (dropped for aadhaar_no field in reports table)
==========
id, report_id, citizen_id, timestamps

citizen
=========

id, aadhar_no, username, name, dob, email, phone, passwd, role, orga_name, timestamps
