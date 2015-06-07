# cia
CIA: Crime Investigation using Aadhaar


# Development

1) Install composer and then execute the command

`composer install`

2) Copy over the file `config/config.sample.cfg` to `config/config.cfg` and modify it accordingly

3) Copy over the vhost config file `sudo cp config/cia.local.conf /etc/apache2/sites-available/cia.local.conf`

4) Enable the vhost copied above `sudo a2ensite cia.local.conf`

5) Reload Apache's config files `sudo service apache2 reload`

6) Append *127.0.0.1  cia.local* to the file `/etc/hosts`

7) Use the database schema at `schema/cia.sql` to create the database

8) Use the sequenced migrations in the folder `schema/migrations` to update the database
 (the once which starts with `1_` goes first then with `2_` and so on)

9) Open `http://cia.local/` on your browser