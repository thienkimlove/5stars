set :application, "5stars.vn"


set :repository,  "file:///opt/repositories/svn/repos/trunk/5star-wap"
set :deploy_via, :copy
set :copy_exclude, [".svn/*", ".buildpath"]
set :copy_compression, :gzip

# Configuration of you app path in the repo
set :cakephp_app_path, "public_html/app"

# Nice optional configurations
set :use_sudo, false # don't need this on most setup
set :keep_releases, 4  # only keep 10 version to save space

# Config files
set :config_files, %w(site.php)

# Persistend dirs
set :persisten_dirs, %w(files)

# Task to set the repository depending on the tag command line parameter
# To pass a tag name execute cap production deploy -s tag=tagname
task :set_repository do
	if exists?(:tag) and tag != ""
		set :repository, "file:///opt/repositories/svn/repos/tags/#{tag}/5star-wap"
	else
		set :repository, "file:///opt/repositories/svn/repos/trunk/5star-wap"    
	end
	puts "  * deploying from #{repository}"
end
before :deploy, "set_repository"


# Environements
task :production do
	set :subname, "wap"
	set :user, "wap"
	set :group, "wap"    
	set :password, "tieungao.pro.wap@gdata"
	set :deploy_to, "/home/wap/domains/#{subname}.#{application}/"
	set :copy_remote_dir, "/home/wap/deploy/tmpupload"
	set :copy_dir, "/usr/local/jenkins/stores"
	set :config_file_suffix, "production"
	server "118.69.171.36", :app, :db, :primary => true
	#after "deploy:finalize_update", "deploy:cakephp:testsuite"
end

task :staging do
	set :subname, "demo"
	set :user, "demowap"
	set :group, "demowap"    
	set :password, "tieungao.stage.wap"
	set :deploy_to, "/home/demowap/domains/#{subname}.#{application}/" 
	set :copy_remote_dir, "/home/demowap/deploy/tmpupload"
	set :copy_dir, "/usr/local/jenkins/stores"
	set :config_file_suffix, "staging"
	server "118.69.171.36", :app, :db, :primary => true   
	#after "deploy:finalize_update", "deploy:cakephp:testsuite"
end 

# Custom events configuration
#before "deploy:update", "deploy:web:disable"    
#after "deploy:restart", "deploy:web:enable"
after "deploy:update", "deploy:cleanup"  

# Custom deployment tasks
namespace :deploy do
		
	desc "This is here to overide the original :restart"
	task :restart, :roles => :app do
		# do nothing but overide the default because we don't need to restart a RoR app
		# Clear the cache
		run "chmod 755 #{current_release}/app/Console/cake"
		run "#{current_release}/app/Console/cake cache clear"
	end
	
	desc "Override the original :migrate to use the dc migration plugin"
	  task :migrate, :roles => :db, :only => { :primary => true } do		
	  end
	
	task :finalize_update, :roles => :app do
		# link a custom configuration file for environment specifics
		# rename configuration files
		config_files.each do |file| 
			run "mv #{current_release}/app/Config/#{file}.#{config_file_suffix} #{current_release}/app/Config/#{file}"
		end
		# you may link here upload file folders if you have any, which should be placed in #{deploy_to}/#{shared_dir} which won't be overide on each deployment
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
		# overide the rest of the default method
		#fix symlink
		run "chmod 755 #{deploy_to}"
		run "chmod 755 #{deploy_to}releases"
		run "rm -f #{deploy_to}#{current_dir}"
		run "ln -s ./domains/#{subname}.#{application}/#{version_dir}/#{release_name} #{deploy_to}#{current_dir}"
	end
		
	namespace :web do
		
		desc "Lock the current access during deployment"
		task :disable, :roles => :app do
			run "touch #{current_release}/#{cakephp_app_path}/webroot/.capistrano-lock"
		end
		
		desc "Enable the current access after deployment"
		task :enable, :roles => :app do
			run "rm #{current_release}/#{cakephp_app_path}/webroot/.capistrano-lock"
		end
	
	end
	
	namespace :cakephp do
	
		desc "Verify CakePHP TestSuite pass"
		task :testsuite, :roles => :app do
			run "#{current_release}/app/Console/cake testsuite app all -app #{release_path}/#{cakephp_app_path}", :env => { :TERM => "linux" } do |channel, stream, data|
				if stream == :err then
					error = CommandError.new("CakePHP TestSuite failed")
					raise error
				else
					puts data
				end
			end
		end
	
	end

end 