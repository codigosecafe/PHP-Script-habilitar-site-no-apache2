<?php
namespace App\Traits;



trait Helpers
{

    public function exec_shell($command, $echo = false)
    {
        return shell_exec($command);
    }

    public function msg($msg, $cor = 'green', $bold = false)
    {
        $msg = '<fg='.$cor.((!$bold)?'':';options=bold').'>'.$msg.'</>';
        return $this->outputMsg->writeln([$msg]);
    }
}
