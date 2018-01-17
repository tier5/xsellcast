<?php namespace App\Storage\FullContact;

use Html;

class ContactInfo
{
	protected $websites;

	protected $lastname;

	protected $firstname;

    protected $city;

    protected $state;

    protected $location;

    protected $gender = null;

    protected $orgs;

    protected $has_info;

    protected $photos = null;

	public function __construct($info)
	{

        $this->has_info = (isset($info->contactInfo));

        if(isset($info->organizations) && is_array($info->organizations))
        {
            foreach($info->organizations as $org)
            {
                $this->orgs[] =  $org->name;
            }
        }

		if(isset($info->contactInfo->websites))
		{
			$this->setWebsites($info->contactInfo->websites);	
		}
        
        if(isset($info->demographics->gender))
        {
            $this->gender =  $info->demographics->gender;
        }        

		if(isset($info->contactInfo->familyName))
		{
			$this->setLastname($info->contactInfo->familyName);
		}
		
		if(isset($info->contactInfo->givenName))
		{
			$this->setFirstname($info->contactInfo->givenName);
		}

        if(isset($info->demographics->locationDeduced->city->name))
        {
            $this->city =  $info->demographics->locationDeduced->city->name;
        }

        if(isset($info->demographics->locationDeduced->state->name))
        {
            $this->state =  $info->demographics->locationDeduced->state->name;
        }       

        if(isset($info->demographics->locationDeduced->normalizedLocation))
        {
            $this->location = $info->demographics->locationDeduced->normalizedLocation;
        }

        if(isset($info->photos) && is_array($info->photos))
        {
            foreach ($info->photos as $photo) {
                $this->setPhotos($photo->url);
            }
        }        
	}

    public function hasInfo()
    {

        return $this->has_info;
    }

    /**
     * Gets the value of websites.
     *
     * @return mixed
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    public function viewWebsites()
    {
        $html = array();
        if($this->websites && is_array($this->websites))
        {
            foreach($this->websites as $key => $url)
            {
                $html[] = Html::link($url, $url, ['target' => '_blank']);
            }

            return implode(', ', $html);
        }
        return null;
    }

    /**
     * Sets the value of websites.
     *
     * @param mixed $websites the websites
     *
     * @return self
     */
    protected function setWebsites($websites)
    {
    	foreach ($websites as $row) {

    		$this->websites[] = $row->url;
    	}

        return $this;
    }

    /**
     * Gets the value of lastname.
     *
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the value of lastname.
     *
     * @param mixed $lastname the lastname
     *
     * @return self
     */
    protected function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets the value of firstname.
     *
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the value of firstname.
     *
     * @param mixed $firstname the firstname
     *
     * @return self
     */
    protected function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets the value of city.
     *
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the value of city.
     *
     * @param mixed $city the city
     *
     * @return self
     */
    protected function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets the value of state.
     *
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the value of state.
     *
     * @param mixed $state the state
     *
     * @return self
     */
    protected function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Gets the value of location.
     *
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function viewLocation()
    {

        return ($this->city && $this->state ? $this->city . ', ' . $this->state : null);
    }

    /**
     * Sets the value of location.
     *
     * @param mixed $location the location
     *
     * @return self
     */
    protected function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Gets the value of gender.
     *
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the value of gender.
     *
     * @param mixed $gender the gender
     *
     * @return self
     */
    protected function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Gets the value of orgs.
     *
     * @return mixed
     */
    public function getOrgs()
    {
        return $this->orgs;
    }

    public function viewOrgs()
    {
        return (is_array($this->orgs) ? implode(', ', $this->orgs) : null );
    }

    /**
     * Sets the value of orgs.
     *
     * @param mixed $orgs the orgs
     *
     * @return self
     */
    protected function setOrgs($orgs)
    {
        $this->orgs = $orgs;

        return $this;
    }

    /**
     * Gets the value of has_info.
     *
     * @return mixed
     */
    public function getHasInfo()
    {
        return $this->has_info;
    }

    /**
     * Sets the value of has_info.
     *
     * @param mixed $has_info the has info
     *
     * @return self
     */
    protected function setHasInfo($has_info)
    {
        $this->has_info = $has_info;

        return $this;
    }

    /**
     * Gets the value of photos.
     *
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    public function viewPhotos()
    {

        if(!$this->photos)
        {

            return null;
        }

        $html = '';
        foreach($this->photos as $url)
        {
            $html .= Html::image($url, null, ['width' => 70, 'class' => 'image thumbnail pull-left']);
        }

        return $html;
    }

    /**
     * Sets the value of photos.
     *
     * @param mixed $photos the photos
     *
     * @return self
     */
    protected function setPhotos($photo_url)
    {
        $this->photos[] = $photo_url;

        return $this;
    }
}