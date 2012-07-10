<?php
if (isset($_GET["act"]) && $_GET["act"] == "search") {
    $url = 'http://search.twitter.com/search.json?q='.$_GET["q"];

    $content = file_get_contents($url);
    $array = json_decode($content);

    $data = array();

    foreach ($array->results as $var => $value)
    {
        // adding url
        $pattern = '/\b(https?:\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$])/i';
        preg_match_all($pattern, $value->text, $regs);
        $loop = count($regs[0]);

        for ($i = 0; $i < $loop; $i++) {
            $value->text = str_replace($regs[0][$i], '<a class="outlink" href="'.$regs[0][$i].'">'.$regs[0][$i].'</a>', $value->text);
        }

        $data[] = array(
            "profile_image_url" => $value->profile_image_url,
            "from_user"         => $value->from_user,
            "text"              => $value->text
        );
    }

    $out = array(
        "success" => true,
        "results" => $data
    );

    echo json_encode($out);
    exit;
}
?>