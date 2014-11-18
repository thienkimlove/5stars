<?php 

class CacheShell extends AppShell {
    
    public function main() {
        $this->out('Cache Shell');
        $this->out('available action:');
        $this->out(' - clear ($cake cache clear)');
    }
    
    public function clear() {
        $this->out('Cache Shell');
        $this->out('Clearing the application cache ...');
        Cache::clear();
        $this->out('Done');
    }
}

?>