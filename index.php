<?php
$file = "/Users/YOUR-USERNAME/.ssh/config";

$contents = file_get_contents($file);
$rows = explode("\n", $contents);
array_shift($rows);

echo "<pre>";
$hosts = [];
foreach ($rows as $row) {
    if (preg_match('/Host /', $row)) {
        $data = explode(' ', $row);
        $hosts[] = $data[1];
    }
}
echo print_r($hosts, true);

// construct .shuttle.json configuration file.
$shuttle = array(
    '_comments' => array(
        'Valid terminals include: \'Terminal.app\' or \'iTerm\'',
        'In the editor value change \'default\' to \'nano\', \'vi\', or another terminal based editor.',
        'Hosts will also be read from your ~/.ssh/config or /etc/ssh_config file, if available',
        'For more information on how to configure, please see http://fitztrev.github.io/shuttle/'
    ),
    'editor' => 'default',
    'launch_at_login' => false,
    'terminal' => 'Terminal.app',
    'iTerm_version' => 'nightly',
    'default_theme' => 'Pro',
    'open_in' => 'new',
    'show_ssh_config_hosts' => false,
    'ssh_config_ignore_hosts' => array(),
    'ssh_config_ignore_keywords'=> array(),
    'hosts' => array()
);

foreach ( $hosts as $host )
{
    $shuttle['hosts'][] = array(
        'name' => $host,
        'cmd' => 'ssh ' . $host
    );
};
// convert to JSON string.
$shuttle_json = json_encode( $shuttle );
// write the file to the $dst.
$bytes = file_put_contents( '.shuttle.json', $shuttle_json );

