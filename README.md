# SemMap
Version 0.0.2 (2015-10-09).
 SemMap (SEMantic MAP) allows you to perform calculations on semantic frames for the German language. A semantic frame is a cluster of words that have the same semantic meaning. The representation of the semantic map was realized JSON. By the way, SemMap is the largest semantic representation of German words that is available to the public. Below you can see a sample frame:

 <pre>
 "Commerce_collect": [
    "kassieren",
     "bitten",
     "verlangen",
     "berechnen",
     "nehmen",
     "essen",
     "futtern",
     "einkassieren"
],
 </pre>

 Semmap also provides correlations between frames, which are organized as matrices. The matrix follows the structure that is explained below:
 <pre>
 "Commerce": {
         "Memory": 0.00024714220074908,
         "Choosing": 0.0048732264936439,
         "Kinship": 0.016520237813453,
         "Assessing": 0.00043510950836106,
         "Deserving": 0.00019492905974576,
         "Destroying": 0.00018796730761198,
         "Part_ordered_segments": 0.016509795185252,
         "Part_orientational": 0.008117402987984,
         "Attention_getting": 0.0039403517077178,
         ...
         "Direction": 0.018431238774175
     },
 </pre>
These correlations are calculated on a German Wikipedia corpus using ~1.3 million documents.

# Use Cases
The possible use cases for SemMap are very wide. The obvious use cases are listed below:

* The SemMap can be used to find semantic frames of words in the text.
* Correlation maps can be used to build a vector representation and therefore a feature space for text classification.
* Self trained correlation maps can be used to cluster your input before running a classification on it.
* Correlation maps are useful to tackle intention analysis and deep semantic understanding of text.


# How it works
You can train such a correlation map on your own by using the train.php file and the directory that contains your training documents as first parameter.
<pre>
php train.php train_docs/
</pre>
The script offers a few constants to adjust the training.

# License & Feedback
SemMap project is released under GPLv3 (http://www.gnu.org/licenses/gpl.html).
I would love to get feedback to this project from other developers as well as companies who are interested in NLP and machine learning.
