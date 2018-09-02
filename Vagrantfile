# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "debian/stretch64"

  config.vm.network :private_network, ip: "192.168.50.10"
  #config.vm.network :forwarded_port, guest: 6379, host: 16379

  config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

  config.vm.provider "virtualbox" do |vb|
    vb.name = "Phalcon Project"
    vb.memory = 8192
    vb.cpus = 4
    vb.customize ["modifyvm", :id, "--hwvirtex", "on"]
    vb.customize ["modifyvm", :id, "--nestedpaging", "on"]
  end

  config.vm.provision :shell,
    path: "bootstrap.sh",
    privileged: false

  config.vm.synced_folder ".", "/vagrant",
    type: "virtualbox",
    owner: "vagrant",
    group: "www-data",
    mount_options: ["dmode=775,fmode=775"]

  config.vm.provision :shell,
    run: "always",
    inline: <<-SHELL
      sudo service php7.2-fpm restart
      sudo service nginx restart
    SHELL
    
end
