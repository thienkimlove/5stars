set :application, "api"

set :scm_username, "hung"
set :scm_password, "hung1234"

# Deploy settings
# see https://github.com/peritor/webistrano/wiki/Configuration-Parameters
set :deploy_via, :export
 
#set :copy_exclude, [".svn/*", ".buildpath"]
set :copy_compression, :gzip
 
# Options
set :use_sudo, false
set :keep_releases, 5
after "deploy:update", "deploy:cleanup" 
  
# Config files
set :config_files, %w(database.php email.php 5star.php)

# Persistend dirs
set :persisten_dirs, %w()

# Task to set the repository depending on the tag command line parameter
# To pass a tag name execute cap production deploy -s tag=tagname
task :set_repository do
	set :repository, "http://118.69.171.36/svn/5stars/trunk/api"
	puts "  * deploying from #{repository}"
end
before :deploy, "set_repository"

set :deploy_to, "/var/www/#{application}"
set :user, "root"
set :group, "root"    
set :password, "tieungao1"

# Environments
task :staging do
	set :config_file_suffix, 'staging'
	server "118.69.171.36", :app, :db, :primary => true
end

task :production do
	set :config_file_suffix, 'production'
	server "118.69.171.36", :app, :db, :primary => true
end

# Deployment tasks
namespace :deploy do
  
  desc "Override the original :restart"
  task :restart, :roles => :app do
	# Clear the cache
	run "#{current_release}/app/Console/cake cache clear"
  end
 
  desc "Override the original :migrate to use the dc migration plugin"
  task :migrate, :roles => :db, :only => { :primary => true } do
	run "#{current_release}/app/Console/cake Migrations.migration run all"
	# Set permissions
	run "chown -R #{user}:#{group} #{current_release}/app/tmp"
	run "chmod -R g+w #{current_release}/app/tmp/*"
  end
  
  desc "Override the original :finalize_update. Set configuration files"
  task :finalize_update, :roles => :app do

	# rename configuration files
	config_files.each do |file| 
		run "mv #{current_release}/app/Config/#{file}.#{config_file_suffix} #{current_release}/app/Config/#{file}"
	end
	
	# Link persisten dirs/files
	persisten_dirs.each do |dir|
		run "rm -fr #{current_release}/app/webroot/#{dir}"
		unless File.directory? "#{shared_path}/#{dir}"
			run "mkdir -p #{shared_path}/#{dir}"
		end
		run "ln -s #{shared_path}/#{dir} #{current_release}/app/webroot/#{dir}"
	end
	
	# Sett permissions
	run "chown -R #{user}:#{group} #{current_release}/app/tmp"
	run "chmod -R g+w #{current_release}/app/tmp"

  end
end
