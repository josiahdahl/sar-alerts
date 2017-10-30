# SAR Dashboard

A modular dashboard that scrapes data/apis for relevant information and displays
it in an easy to consume way. Built with Laravel and Vue.js.

## Setup
### Development
1. Clone the project, switch to the directory, and run `vagrant up`  
2. Install RabbitMQ (TODO: Include this in provisioning Vagrant Box)
3. Install pip3 `$ sudo apt install python3-pip`

### RabbitMQ
1. Create a new user. `$ sudo rabbitmqctl add_user myuser mypassword`  
2. Add a virtual host. `$ sudo rabbitmqctl add_vhost myvhost`  
3. Allow user to access vhost. `$ sudo rabbitmqctl set_user_tags myuser mytag` `$ sudo
rabbitmqctl set_permissions -p myvhost myuser ".*" ".*" ".*"`

