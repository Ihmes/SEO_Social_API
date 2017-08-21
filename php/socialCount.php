<?

class SocialButtons {

    public function __construct() {

      header('Content-Type: application/json');

      $this->url = 'https://www.advertising.de/sitecheck/';

      $out = array (
       'facebook'   => $this->getResultFacebook(),
       'twitter'    => $this->getResultTwitter(),
       'googleplus' => $this->getResultGoogle(),
       'linkedin'   => $this->getResultLinkedin(),
       'xing'       => $this->getResultXing(),
       'requestUrl' => $this->url
      ) ;

      echo json_encode($out);

    }


  private function getResultFacebook() {

    $url = $this->url;

    $request = 'https://api.facebook.com/method/fql.query?query=select%20%20total_count%20from%20link_stat%20where%20url=%22'.$url.'%22';
    $file = file_get_contents($request);

    if(empty($file)) return 0;

    preg_match('!<total_count>(.*)</total_count>!isU', $file, $count);
    if(!count($count)) return 0;

    return intval($count[1]);

  }


  private function getResultTwitter() {

    $url = $this->url;

    $request = 'http://urls.api.twitter.com/1/urls/count.json?url='.urlencode($url);
    $file = file_get_contents($request);

    if(empty($file)) return 0;

    $json = json_decode($file, true);
    if(!$json) return 0;

    return intval($json['count']);

  }


  private function getResultGoogle() {

    $url = $this->url;

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://clients6.google.com/rpc');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $output = curl_exec($ch); 
    curl_close($ch);

    $data = json_decode($output, true);

    if(empty($data[0]['result']['metadata']['globalCounts']['count'])) return 0;

    return intval($data[0]['result']['metadata']['globalCounts']['count']);

  }


  private function getResultPinterest() {

    $url = $this->url;
    echo $request = 'http://api.pinterest.com/v1/urls/count.json?callback=super&url=' . urlencode($url);
    if(empty($file)) return 0;

  }


  private function getResultLinkedin() {

    $url = $this->url;

    $request = 'http://www.linkedin.com/countserv/count/share?url='.urlencode($url).'&format=json';
    $file = file_get_contents($request);

    if(empty($file)) return 0;

    $json = json_decode($file, true);
    return isset($json['count'])?intval($json['count']):0;

  }


  private function getResultXing() {

    $url = $this->url;

    $request = 'https://www.xing-share.com/app/share?op=get_share_button;counter=top;url='.urlencode($url);
    $file = file_get_contents($request);

    if(empty($file)) return 0;

    preg_match('@<span class="xing-count top">(.*)</span>@isU', $file, $result);
    if(!is_array($result)) return 0;
    if(!count($result)) return 0;

    return intval($result[1]);

  }


}

new SocialButtons;

?>