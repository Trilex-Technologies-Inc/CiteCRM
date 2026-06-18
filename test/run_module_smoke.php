<?php
// Minimal smoke test for modules/control/modules.php
chdir(__DIR__ . '/..');
define('SEP', DIRECTORY_SEPARATOR);
define('PRFX', 'crm_');

// Fake in-memory DB shim that records module rows and responds to simple queries
class FakeDB {
    public $data = array('modules' => array());
    private $lastError = '';
    public function Execute($sql) {
        $s = trim($sql);
        // ignore CREATE TABLE
        if (stripos($s, 'CREATE TABLE') === 0) return true;
        // SELECT COUNT(*) AS c FROM PRFX.MODULES WHERE MODULE_DIR='x'
        if (preg_match('/SELECT\s+COUNT\(\*\)\s+AS\s+c\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $obj = new stdClass();
            $obj->fields = array('c' => count($this->data['modules']));
            return $obj;
        }
        // SELECT * ... WHERE MODULE_DIR=... LIMIT 1
        if (preg_match('/SELECT\s+\*\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)\s+LIMIT\s+1/i', $s, $m)) {
            $dir = trim($m[1], "'\" ");
            foreach ($this->data['modules'] as $row) {
                if ($row['MODULE_DIR'] === $dir) {
                    $res = new stdClass();
                    $res->fields = $row;
                    $res->EOF = false;
                    return $res;
                }
            }
            $res = new stdClass(); $res->fields = array(); $res->EOF = true; return $res;
        }
        // INSERT INTO ... MODULES (...) VALUES (...)
        if (preg_match('/INSERT\s+INTO\s+.+MODULES\s*\(([^\)]+)\)\s*VALUES\s*\(([^\)]+)\)/i', $s, $m)) {
            $cols = array_map('trim', explode(',', $m[1]));
            $vals = array_map('trim', explode(',', $m[2]));
            $row = array();
            for ($i = 0; $i < count($cols); $i++) {
                $c = trim($cols[$i]);
                $v = isset($vals[$i]) ? trim($vals[$i]) : 'NULL';
                $v = trim($v, "'\" ");
                $row[$c] = $v;
            }
            // normalize keys to expected names
            $norm = array();
            foreach ($row as $k => $v) {
                $nk = strtoupper(trim($k));
                $norm[$nk] = $v;
            }
            // ensure MODULE_DIR exists
            if (!isset($norm['MODULE_DIR']) && isset($row['MODULE_DIR'])) $norm['MODULE_DIR'] = $row['MODULE_DIR'];
            // default fields
            $norm = array_merge(array('MODULE_NAME'=>'','MODULE_DIR'=>'','MODULE_VERSION'=>'','MODULE_AUTHOR'=>'','MODULE_DESC'=>'','INSTALLED'=>0,'ENABLED'=>0), $norm);
            $this->data['modules'][] = $norm;
            return true;
        }
        // UPDATE ENABLED
        if (preg_match('/UPDATE\s+.+MODULES\s+SET\s+ENABLED=(.+)\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $val = trim($m[1]); $dir = trim($m[2], "'\" ");
            foreach ($this->data['modules'] as &$row) {
                if ($row['MODULE_DIR'] === $dir) { $row['ENABLED'] = trim($val, "'\" "); }
            }
            return true;
        }
        // DELETE FROM MODULES WHERE MODULE_DIR=...
        if (preg_match('/DELETE\s+FROM\s+.+MODULES\s+WHERE\s+MODULE_DIR=(.+)/i', $s, $m)) {
            $dir = trim($m[1], "'\" ");
            foreach ($this->data['modules'] as $k => $row) {
                if ($row['MODULE_DIR'] === $dir) unset($this->data['modules'][$k]);
            }
            // reindex
            $this->data['modules'] = array_values($this->data['modules']);
            return true;
        }
        return true;
    }
    public function ErrorMsg() { return $this->lastError; }
    public function qstr($s) { return "'" . str_replace("'", "''", $s) . "'"; }
    public function StartTrans() { }
    public function FailTrans() { }
    public function CompleteTrans() { return true; }
}

// Minimal Smarty stub
class SmartyStub {
    public $assigned = array();
    public function assign($k, $v) { $this->assigned[$k] = $v; }
    public function display($t) { echo "TEMPLATE: $t\n"; if (!empty($this->assigned['exec_log'])) { echo "--- EXEC LOG ---\n"; foreach ($this->assigned['exec_log'] as $l) echo $l . "\n"; } if (!empty($this->assigned['msg'])) { echo "MSG: " . $this->assigned['msg'] . "\n"; } }
}

// create temp sqlite file
// fake DB instance (in-memory)
$db = new FakeDB();

// Create modules table similar to modules.php expects (modules.php also creates it, but ensure exists)
$create = "CREATE TABLE IF NOT EXISTS " . PRFX . "MODULES (
    MODULE_ID INTEGER PRIMARY KEY AUTOINCREMENT,
    MODULE_NAME TEXT NOT NULL,
    MODULE_DIR TEXT NOT NULL,
    MODULE_VERSION TEXT DEFAULT '',
    MODULE_AUTHOR TEXT DEFAULT '',
    MODULE_DESC TEXT,
    INSTALLED INTEGER DEFAULT 0,
    ENABLED INTEGER DEFAULT 0
);";
$db->Execute($create);

// provide globals expected by modules.php
$smarty = new SmartyStub();

// simulate POST for register_all then install/uninstall flows
// first run register_all
$_POST = array('action' => 'register_all');
include 'modules/control/modules.php';

// show recorded DB 'modules' rows
echo "\n-- DB MODULES TABLE --\n";
$rows = $db->data['modules'];
foreach ($rows as $r) {
    echo "DIR: {$r['MODULE_DIR']} | NAME: {$r['MODULE_NAME']} | INSTALLED: {$r['INSTALLED']} | ENABLED: {$r['ENABLED']}\n";
}

// Now test uninstall for one module that we registered (if any): uninstall first module
if (count($rows) > 0) {
    $first = $rows[0]['MODULE_DIR'];
    echo "\n-- Testing uninstall for: $first --\n";
    // simulate confirmation and run uninstall
    $_POST = array('action' => 'uninstall', 'module_dir' => $first, 'confirm_uninstall' => 'yes');
    include 'modules/control/modules.php';
    // show fake db after uninstall
    echo "\n-- DB MODULES TABLE AFTER UNINSTALL --\n";
    $rows2 = $db->data['modules'];
    foreach ($rows2 as $r) {
        echo "DIR: {$r['MODULE_DIR']} | NAME: {$r['MODULE_NAME']} | INSTALLED: {$r['INSTALLED']} | ENABLED: {$r['ENABLED']}\n";
    }
}

echo "\nSmoke test complete.\n";
