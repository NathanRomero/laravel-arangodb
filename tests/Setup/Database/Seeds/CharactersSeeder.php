<?php

namespace Tests\Setup\Database\Seeds;

use Illuminate\Database\Seeder;
use Tests\Setup\Models\Character;

class CharactersSeeder extends Seeder
{
    /**
     * Run the database Seeds.
     *
     * @return void
     */
    public function run()
    {
        $characters = '[
           {
              "_key":"NedStark",
              "name":"Ned",
              "surname":"Stark",
              "alive":true,
              "age":41,
              "residence_key":"winterfell"
           },
           {
              "_key":"RobertBaratheon",
              "name":"Robert",
              "surname":"Baratheon",
              "alive":false,
              "residence_key":"winterfell"
           },
           {
              "_key":"JaimeLannister",
              "name":"Jaime",
              "surname":"Lannister",
              "alive":true,
              "age":36,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"CatelynStark",
              "name":"Catelyn",
              "surname":"Stark",
              "alive":false,
              "age":40,
              "residence_key":"winterfell"
           },
           {
              "_key":"CerseiLannister",
              "name":"Cersei",
              "surname":"Lannister",
              "alive":true,
              "age":36,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"DaenerysTargaryen",
              "name":"Daenerys",
              "surname":"Targaryen",
              "alive":true,
              "age":16,
              "residence_key":"winterfell"
           },
           {
              "_key":"JorahMormont",
              "name":"Jorah",
              "surname":"Mormont",
              "alive":false,
              "residence_key":"winterfell"
           },
           {
              "_key":"PetyrBaelish",
              "name":"Petyr",
              "surname":"Baelish",
              "alive":false,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"ViserysTargaryen",
              "name":"Viserys",
              "surname":"Targaryen",
              "alive":false,
              "residence_key":null
           },
           {
              "_key":"JonSnow",
              "name":"Jon",
              "surname":"Snow",
              "alive":true,
              "age":16,
              "residence_key":"winterfell"
           },
           {
              "_key":"SansaStark",
              "name":"Sansa",
              "surname":"Stark",
              "alive":true,
              "age":13,
              "residence_key":"winterfell"
           },
           {
              "_key":"AryaStark",
              "name":"Arya",
              "surname":"Stark",
              "alive":true,
              "age":11,
              "residence_key":"winterfell"
           },
           {
              "_key":"RobbStark",
              "name":"Robb",
              "surname":"Stark",
              "alive":false,
              "residence_key":"winterfell"
           },
           {
              "_key":"TheonGreyjoy",
              "name":"Theon",
              "surname":"Greyjoy",
              "alive":true,
              "age":16,
              "residence_key":"winterfell"
           },
           {
              "_key":"BranStark",
              "name":"Bran",
              "surname":"Stark",
              "alive":true,
              "age":10,
              "residence_key":"winterfell"
           },
           {
              "_key":"JoffreyBaratheon",
              "name":"Joffrey",
              "surname":"Baratheon",
              "alive":false,
              "age":19,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"SandorClegane",
              "name":"Sandor",
              "surname":"Clegane",
              "alive":true,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"TyrionLannister",
              "name":"Tyrion",
              "surname":"Lannister",
              "alive":true,
              "age":32,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"KhalDrogo",
              "name":"Khal",
              "surname":"Drogo",
              "alive":false,
              "residence_key":"winterfell"
           },
           {
              "_key":"TywinLannister",
              "name":"Tywin",
              "surname":"Lannister",
              "alive":false,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"DavosSeaworth",
              "name":"Davos",
              "surname":"Seaworth",
              "alive":true,
              "age":49,
              "residence_key":"winterfell"
           },
           {
              "_key":"SamwellTarly",
              "name":"Samwell",
              "surname":"Tarly",
              "alive":true,
              "age":17,
              "residence_key":"winterfell"
           },
           {
              "_key":"StannisBaratheon",
              "name":"Stannis",
              "surname":"Baratheon",
              "alive":false,
              "residence_key":"dragonstone"
           },
           {
              "_key":"Melisandre",
              "name":"Melisandre",
              "alive":true,
              "residence_key":"dragonstone"
           },
           {
              "_key":"MargaeryTyrell",
              "name":"Margaery",
              "surname":"Tyrell",
              "alive":false,
              "residence_key":"winterfell"
           },
           {
              "_key":"JeorMormont",
              "name":"Jeor",
              "surname":"Mormont",
              "alive":false,
              "residence_key":null
           },
           {
              "_key":"Bronn",
              "name":"Bronn",
              "alive":true,
              "residence_key":"king-s-landing"
           },
           {
              "_key":"Varys",
              "name":"Varys",
              "alive":true,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"Shae",
              "name":"Shae",
              "alive":false,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"TalisaMaegyr",
              "name":"Talisa",
              "surname":"Maegyr",
              "alive":false
           },
           {
              "_key":"Gendry",
              "name":"Gendry",
              "alive":false,
              "residence_key":"king-s-landing"
           },
           {
              "_key":"Ygritte",
              "name":"Ygritte",
              "alive":false,
              "residence_key":"beyond-the-wall"
           },
           {
              "_key":"TormundGiantsbane",
              "name":"Tormund",
              "surname":"Giantsbane",
              "alive":true,
              "residence_key":"beyond-the-wall"
           },
           {
              "_key":"Gilly",
              "name":"Gilly",
              "alive":true,
              "residence_key":"beyond-the-wall"
           },
           {
              "_key":"BrienneTarth",
              "name":"Brienne",
              "surname":"Tarth",
              "alive":true,
              "age":32
           },
           {
              "_key":"RamsayBolton",
              "name":"Ramsay",
              "surname":"Bolton",
              "alive":true
           },
           {
              "_key":"EllariaSand",
              "name":"Ellaria",
              "surname":"Sand",
              "alive":true
           },
           {
              "_key":"DaarioNaharis",
              "name":"Daario",
              "surname":"Naharis",
              "alive":true
           },
           {
              "_key":"Missandei",
              "name":"Missandei",
              "alive":true
           },
           {
              "_key":"TommenBaratheon",
              "name":"Tommen",
              "surname":"Baratheon",
              "alive":true,
              "residence_key":"the-red-keep"
           },
           {
              "_key":"JaqenHghar",
              "name":"Jaqen",
              "surname":"H\'ghar",
              "alive":true
           },
           {
              "_key":"RooseBolton",
              "name":"Roose",
              "surname":"Bolton",
              "alive":true
           },
           {
              "_key":"TheHighSparrow",
              "name":"The High Sparrow",
              "alive":true,
              "residence_key":"king-s-landing"
           }
        ]';

        $characters = json_decode($characters, JSON_OBJECT_AS_ARRAY);

        foreach ($characters as $character) {
            Character::insert($character);
        }
    }
}
