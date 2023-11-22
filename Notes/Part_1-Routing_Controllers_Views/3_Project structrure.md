# Project Structure ..

Create the following directories .. 

```
core/
public/ 
controllers/
models/
views/
runtime/    for user generated data ...(caching files ,temporary pdf files) .. gitignored.

```

#### ```runtime/```
This directory is for user generated data. It is gitignored. 
- you create a ```.gitignore``` file . ```ignore everything in the directory  except the gitignore file``` ..

```
runtime/
    .gitignore
        *
        !.gitignore

```

Create a ```.gitignore``` in the ```root dir``` and ignore the :  
-  ```vender/```
-  ```idea/``` : config folder for the php store




