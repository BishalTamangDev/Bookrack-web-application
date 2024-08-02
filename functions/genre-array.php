<?php
$genreArray = array(
    "Adventure",
    "Art",
    "Autobiography",
    "Biography",
    "Business",
    "Chick Lit",
    "Children's",
    "Classics",
    "Comics",
    "Contemporary",
    "Cookbooks",
    "Crime",
    "Drama",
    "Dystopian",
    "Economics",
    "Education",
    "Erotica",
    "Essays",
    "Fairy Tales",
    "Fantasy",
    "Fiction",
    "Folklore",
    "Graphic Novels",
    "Historical Fiction",
    "History",
    "Horror",
    "Humor",
    "Inspirational",
    "LGBTQ+",
    "Literary Fiction",
    "Manga",
    "Memoir",
    "Mystery",
    "Mythology",
    "New Adult",
    "Non-Fiction",
    "Paranormal",
    "Philosophy",
    "Poetry",
    "Politics",
    "Psychological Fiction",
    "Psychology",
    "Religion",
    "Romance",
    "Satire",
    "Science",
    "Science Fiction",
    "Self-Help",
    "Short Stories",
    "Spirituality",
    "Sports",
    "Steampunk",
    "Suspense",
    "Technology",
    "Thriller",
    "Travel",
    "True Crime",
    "Urban Fantasy",
    "Western",
    "Women's Fiction",
    "Young Adult (YA)"
);

// get array index value
// function getArrayIndexValue($dataToBeSearched, $which)
// {
//     global $districtArray;

//     if ($which == "district") {
//         $index = array_search($dataToBeSearched, $districtArray);
//     } else {
//         $index = "";
//     }

//     return $index;
// }

function getGenreIndexValue($dataToBeSearched)
{
    global $genreArray;
    $index = "";
    $index = array_search($dataToBeSearched, $genreArray);
    return $index;
}