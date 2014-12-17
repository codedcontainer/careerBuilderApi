<?php

$apiKey = ''; 
$companyName = ""; //this can be grabbed from the url
$hostSite = 'US'; //keeps bugs out 

$service_url = 'http://api.careerbuilder.com/v1/jobsearch?DeveloperKey='.$apiKey.'&CompanyName='.$companyName;
$service_url .= '&HostSite='.$hostSite;
// echo $service_url; 
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPGET, true);
 // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);

$curl_response = curl_exec($curl);
curl_close($curl);

$xml = simplexml_load_string($curl_response); 

//print_r($xml); 
$count = count($xml->Results->JobSearchResult);
$jobs = $xml->Results->JobSearchResult; 
 
for ($i = 0; $i <= $count; $i++)
{
  //$company =  $xml->Results->JobSearchResult[$i]->Company;
  //$url = $xml->Results->JobSearchResult[$i]->Company;
  $jobTitle = $xml->Results->JobSearchResult[$i]->JobTitle;
  $jobDetailUrl = $xml->Results->JobSearchResult[$i]->JobDetailsUrl;
  $jobServiceUrl = $xml->Results->JobSearchResult[$i]->JobServiceURL;
  $postedDate = $xml->Results->JobSearchResult[$i]->PostedDate;
  
  $html = "<h2 id='jobTitle'><a href='".$jobDetailUrl."'>".$jobTitle."</a></h2>";
  $html .= "<span id='date'>Posted Date: ".$postedDate."</span>";

 
   //get the job description
  $curl2 = curl_init();
  curl_setopt($curl2, CURLOPT_URL, $jobServiceUrl);
  curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl2, CURLOPT_HTTPGET, true);

  $curl_response2 = curl_exec($curl2);
  curl_close($curl2);

  $xml2 = simplexml_load_string($curl_response2); 

  $jobDescription = strip_tags(html_entity_decode($xml2->Job->JobDescription));
  //change the number of words to display
  $string = $jobDescription;

if (strlen($string) > 200) {

    // truncate string
    $stringCut = substr($string, 0, 200);

}
  $html .= "<p id='jobDescription'>".$stringCut."<a href='".$jobDetailUrl."''> Read More..</a>"."</p>";
  echo $html;
}

    

?>


