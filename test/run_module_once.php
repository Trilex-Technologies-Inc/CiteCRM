<?php
// Run modules/control/modules.php in a single process with file-backed FakeDB
chdir(__DIR__ . '/..');
define('SEP', DIRECTORY_SEPARATOR);
define('PRFX', 'crm_');

$storefile = __DIR__ . '/modules_store.json';
$initial = array('modules' => array());
if (file_exists($storefile)) {
    $initial = json_decode(file_get_contents($storefile), true);
}

class FileDB {
    private $storefile;
    public $data;
    public function __construct($file, $initial) {
        $this->storefile = $file;
        $this->data = $initial;
        if (!isset($this->data['modules'])) $this->data['modules'] = array();
    }
    private function persist() { file_put_contents($this->storefile, json_encode($this->data)); }
    public function Execute($sql) {
        $s = trim($sql);
        if (stripos($s, 'CREATE TABLE') === 0) return true;
        if (preg_match('/SELECT\s+COUNT\(\*\)\s+AS\s+c\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $obj = new stdClass(); $obj->fields = array('c' => count($this->data['modules'])); return $obj;
        }
        if (preg_match('/SELECT\s+\*\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)\s+LIMIT\s+1/i', $s, $m)) {
            $dir = trim($m[1], "'\" ");
            foreach ($this->data['modules'] as $row) {
                if ($row['MODULE_DIR'] === $dir) { $res = new stdClass(); $res->fields = $row; $res->EOF=false; return $res; }
            }
            $res = new stdClass(); $res->fields = array(); $res->EOF=true; return $res;
        }
        if (preg_match('/INSERT\s+INTO\s+.+MODULES\s*\(([^\)]+)\)\s*VALUES\s*\(([^\)]+)\)/i', $s, $m)) {
            $cols = array_map('trim', explode(',', $m[1]));
            $vals = array_map('trim', explode(',', $m[2]));
            $row = array();
            for ($i = 0; $i < count($cols); $i++) {
                $c = trim($cols[$i]); $v = isset($vals[$i]) ? trim($vals[$i]) : 'NULL'; $v = trim($v, "'\" "); $row[$c] = $v;
            }
            $norm = array(); foreach ($row as $k => $v) { $nk = strtoupper(trim($k)); $norm[$nk] = $v; }
            $norm = array_merge(array('MODULE_NAME'=>'','MODULE_DIR'=>'','MODULE_VERSION'=>'','MODULE_AUTHOR'=>'','MODULE_DESC'=>'','INSTALLED'=>0,'ENABLED'=>0), $norm);
            $this->data['modules'][] = $norm; $this->persist(); return true;
        }
        if (preg_match('/UPDATE\s+.+MODULES\s+SET\s+ENABLED=(.+)\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $val = trim($m[1]); $dir = trim($m[2], "'\" ");
            foreach ($this->data['modules'] as &$row) { if ($row['MODULE_DIR'] === $dir) { $row['ENABLED'] = trim($val, "'\" "); } }
            $this->persist(); return true;
        }
        if (preg_match('/DELETE\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $dir = trim($m[1], "'\" "); foreach ($this->data['modules'] as $k => $row) { if ($row['MODULE_DIR'] === $dir) unset($this->data['modules'][$k]); } $this->data['modules'] = array_values($this->data['modules']); $this->persist(); return true;
        }
        return true;
    }
    public function ErrorMsg() { return ''; }
    public function qstr($s) { return "'" . str_replace("'", "''", $s) . "'"; }
    public function StartTrans() {}
    public function FailTrans() {}
    public function CompleteTrans() { return true; }
}

class SmartyStub { public $assigned = array(); public function assign($k,$v){ $this->assigned[$k]=$v; } public function display($t){ echo "TEMPLATE: $t\n"; if(!empty($this->assigned['exec_log'])){ echo "--- EXEC LOG ---\n"; foreach($this->assigned['exec_log'] as $l) echo $l."\n"; } if(!empty($this->assigned['msg'])) echo "MSG: " . $this->assigned['msg'] . "\n"; } }

// create DB backed by file
$db = new FileDB($storefile, $initial);
$smarty = new SmartyStub();

// read CLI args
$action = isset($argv[1]) ? $argv[1] : 'register_all';
$module_dir = isset($argv[2]) ? $argv[2] : null;
$confirm = isset($argv[3]) ? $argv[3] : null;

// build POST
$_POST = array('action' => $action);
if ($module_dir) $_POST['module_dir'] = $module_dir;
if ($confirm) $_POST['confirm_uninstall'] = $confirm;

// include the modules manager
include 'modules/control/modules.php';

// output saved modules
echo "\n-- STORED MODULES (file-backed) --\n";
foreach ($db->data['modules'] as $r) {
    echo "DIR: {$r['MODULE_DIR']} | NAME: {$r['MODULE_NAME']} | INSTALLED: {$r['INSTALLED']} | ENABLED: {$r['ENABLED']}\n";
}

// print any exec_log assigned
if (!empty($smarty->assigned['exec_log'])) {
    echo "\n-- EXEC LOG (captured) --\n";
    foreach ($smarty->assigned['exec_log'] as $l) echo $l . "\n";
}

echo "\nDone.\n";
