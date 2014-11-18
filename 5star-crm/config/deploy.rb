set :application, "5stars.vn"



set :repository,  "file:///opt/repositories/svn/repos/trunk/5star-crm"

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


# Environements
task :production do
    set :subname, "crm"
    set :user, "crm"
    set :group, "crm"    
    set :password, "tieungao.pro.crm@gdata"
    set :deploy_to, "/home/crm/domains/#{subname}.#{application}/"
    set :copy_remote_dir, "/home/crm/deploy/tmpupload"
    set :copy_dir, "/usr/local/jenkins/stores"
    set :config_file_suffix, "production"
    server "118.69.171.36", :app, :db, :primary => true    
end

# Custom events configuration
after "deploy:update", "deploy:cleanup"  



# Custom deployment tasks
namespace :deploy do
        
    desc "This is here to overide the original :restart"
    task :restart do
        # do nothing but overide the default because we don't need to restart a RoR app
        # Clear the cache
        run "chmod 755 #{current_release}/app/Console/cake"
        run "#{current_release}/app/Console/cake cache clear"
    end
    
    desc "Override the original :migrate to use the dc migration plugin"
      task :migrate, :roles => :db, :only => { :primary => true } do        
      end
    
    task :finalize_update do
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

end 