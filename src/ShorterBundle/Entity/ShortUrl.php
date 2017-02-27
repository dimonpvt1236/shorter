<?php
/**
 * Created by PhpStorm.
 * User: dimon
 * Date: 26.02.17
 * Time: 10:32
 */

namespace ShorterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ShortUrl
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShorterBundle\Entity\Repository\ShortUrlRepository")
 */
class ShortUrl
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\Url(
     *    protocols = {"http", "https"}
     * )
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url", type="string", length=40, unique=true)
     * @Assert\Url(
     *    protocols = {"http", "https"}
     * )
     */
    private $shortUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", options={"default" : 0})
     */
    private $count = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     */
    private $createdOn;

    public function __construct()
    {
        $this->createdOn = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @param string $shortUrl
     */
    public function setShortUrl($shortUrl = null)
    {
        if ($shortUrl) {
            $this->shortUrl = $shortUrl;
        } else {
            $this->shortUrl = $this->generateShortUrl();
        }
    }

    private function generateShortUrl($length = 10) {
        $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charsetLength = strlen($charset);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $charset[rand(0, $charsetLength - 1)];
        }
        return $str;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}