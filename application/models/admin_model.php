<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Admin_Model extends CI_Model
{
    public function getHeroIcons()
    {
        $this->load->library('domparser');
        
        $html = new simple_html_dom();
        $html->load_file('http://www.heroesofnewerth.com/heroes.php');
        $imgs = $html->find('img.herolist_herobutton_icon');
        
        foreach($imgs as $img)
        {
            d($img->src);
            $str = explode('/', $img->src);
            
            if($str[1] == 'heroes')
            {
                if(!file_exists('./assets/img/heroes/'.$str[2].'.jpg'))
                {
                    copy('http://www.heroesofnewerth.com/'.$img->src, './assets/img/heroes/'.$str[2].'.jpg');
                }
            }
        }
    }
    
    public function getItemIcons()
    {
        $this->load->library('domparser');
        
        $html = new simple_html_dom();
        $html->load_file('http://www.heroesofnewerth.com/items.php');
        $imgs = $html->find('img.itemlist_button_icon');
        
        foreach($imgs as $img)
        {
            d($img->src);
            $str = explode('/', $img->src);
            
            if($str[1] == 'items')
            {
                if(!file_exists('./assets/img/items/'.$str[2]))
                {
                    copy('http://www.heroesofnewerth.com/'.$img->src, './assets/img/items/'.$str[2]);
                }
            }
        }
    }
	
	public function emptyCache()
	{
		$dossier_traite = "./application/cache/players";
		$repertoire = opendir($dossier_traite);
		 
		while (false !== ($fichier = readdir($repertoire)))
		{
			$chemin = $dossier_traite."/".$fichier;
			 
			if($fichier != ".." && $fichier != "." && !is_dir($fichier))
			{
				unlink($chemin);
				echo "<p>$fichier</p>";
			}
		}
		
		closedir($repertoire);
	}
}
