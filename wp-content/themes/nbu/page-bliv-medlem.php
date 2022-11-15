
<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 *
 * @package ShuttleThemes
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; wp_reset_postdata(); ?>

			<main id="main" class="main">
	
	<!-- Her kommer mit indhold ind -->
	<div class="filteringsheader">
      <nav class="nav_container">
		<div class="grid_fh">
		<button data-sport="fodbold" class="hoverb sport_btn valgtsport">Fodbold</button>
		<button data-sport="handbold" class="hoverb sport_btn">Håndbold</button>
		</div>	
        <button data-hold="alle" class="valgt hold_btn">Alle</button>
        <button data-hold="23" class="hoverb hold_btn">Børn</button>
        <button data-hold="24" class="hoverb hold_btn">Unge</button>
        <button data-hold="1" class="hoverb hold_btn">Senior</button>
      </nav>
      <h2 id="filtreringsoverskrift">Alle</h2>
    </div>

	<section id="medlemsliste"></section>
	<template id="medlemstemplate">
		<article class="holdbeskrivelser">
			<h2></h2>
		<div class="grid_1-1">
			<img src="" alt="" id="picture">
			<h3></h3>
			<p></p>
			<p id="pris"></p>
			<p id="kontigent"></p>
			<button id="indmeld">Indmeld</button>
		</div>
		</article>
	</template>
</main><!-- #main -->
			
			<script>

// ***************** HENTER DATA *************************************
// Tjekker om siden bliver loaded ind med DOMContentLoaded
document.addEventListener("DOMContentLoaded", getJson);	

// Find url til Json
siteUrl = "https://lauraskovsted.dk/kea/09_cms/nbu_wp/wp-json/wp/v2/aldersgruppe?per_page=100"

let medlemmer = [];
// laver variabel, som sættes lige med alle -> så det er den generelle indstilling, når man kommer ind på siden.
let filter = "alle";

// Laver variabel for fodbold og håndbold knapperne - sætter indstillingen til at vise begge sportsgrene først.
let nytfilter = "begge";

// referer til min template
const temp = document.querySelector("#medlemstemplate");

// Her indholdet kommer ind i min section
const container = document.querySelector("#medlemsliste");

// Henter Json
async function getJson(){
const response = await fetch(siteUrl);
medlemmer = await response.json();

console.log(medlemmer);

visMedlemmer();
}

// **************************** filtrering ****************************


// Laver konstant der gør vi får fat i alle knapperne
const filterKnapper = document.querySelectorAll("nav .hold_btn");

// Laver en konstant for de nye filterknapper til håndbold og fodbold.
const sportFilterKnapper = document.querySelectorAll("nav .sport_btn");

// Gør knapperne klikbarer og kalder anonym funktion
filterKnapper.forEach((knap) => knap.addEventListener("click", filtrerHold));

// Gør de nye sportsknapper klikbarer og kalder klikfunktion.
sportFilterKnapper.forEach((sportknap) => sportknap.addEventListener("click", filtrerSport));



// konstant til h2 tekst
const textOverskrift = document.querySelector(".filteringsheader h2");


// ************** sportsfunktion ****************

// Kalder på filtrerSport
function filtrerSport(){
// finder værdien der ligger i knappens data-attribut.
  nytfilter = this.dataset.sport;

// fjerner klassen valgt fra alle
document.querySelector(".valgtsport").classList.remove("valgtsport");

// tilføjer klassen valgt til kategorierne der bliver klikket på
this.classList.add("valgtsport");

// Kalder visMedlemmer funktionen på ny
visMedlemmer();
}

// ************** filtrering af hold funktion *********


// kalder på filtrerHold
function filtrerHold() {
  // finder værdien der ligger i knappens data-attribut.
  filter = this.dataset.hold;

  // fjerner klassen valgt fra alle
  document.querySelector(".valgt").classList.remove("valgt");

  // tilføjer klassen valgt til kategorierne der bliver klikket på
  this.classList.add("valgt");

  //Gør at h2 overskriften passer til den valgte kategori
  textOverskrift.textContent = this.textContent;

  // Kalder visMedlemmer funktionen på ny
  visMedlemmer();
}


// ********************** Loop-view ************************

function visMedlemmer(){
// Gør at man sletter indholdet igen
container.innerHTML = "";
 console.log({nytfilter});
console.log({filter});
medlemmer.forEach((medlem) => { 
//if((filter == medlem.categories[0]|| filter == "alle") && medlem.sport == nytfilter)

if ((filter == medlem.categories[0] || filter == "alle") && (nytfilter == "begge" || nytfilter == medlem.sport)){

// Siger jeg vil lave en klon af indholdet (content) i min template tag.	
const klon = temp.cloneNode(true).content
klon.querySelector("h2").innerHTML = medlem.title.rendered;
klon.querySelector("h3").innerHTML = medlem.overskrift_h3;
klon.querySelector("p").innerHTML = medlem.holdbeskrivelse;
klon.querySelector("#pris").innerHTML = "Indmeldingspris " + medlem.pris;
klon.querySelector("#kontigent").innerHTML = "kontigent " + medlem.kontigent;
klon.querySelector("#picture").src = medlem.billede.guid;

// Gør at indholdet bliver tilføjet til min template
container.appendChild(klon);}
})

}



	</script>

	


<?php get_footer(); ?>