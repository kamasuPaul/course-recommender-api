
### The course elibility algorithm
```
      //declare list of ineligble course
        //declare list of eligble courses
        //Get all courses
        //foreach course
            //get its essential_required subjects
            //get its essential_optional subjects
            //foreach essential_required subject
                //find in the student subject list
                //if not found, add course to ineligble courses list.
                //break out this loop, and continue to next iteration of outer loop
            
            //now look at the essential_optional
            //find the relation on the essential_optional
            //get no of required essential_optional subjects
            //declare array of found subjects
            //foreach subject in the student subject list
                //if found is equal or greater than required, break the loop
                //try and find it in the essential_optional subject list
                //add it to found subjects
            //if found has equal or greater than required subjects, break the loop
            //else add course to eligble list

```