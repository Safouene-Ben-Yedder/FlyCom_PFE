import sys
from csv import writer

 
def append_list_as_row(file_name, list_of_elem):
    # Open file in append mode
    with open(file_name, 'a', newline='\n') as write_obj:
        # Create a writer object from csv module
        csv_writer = writer(write_obj)
        # Add contents of list as last row in the csv file
        csv_writer.writerow(list_of_elem)


p1= sys.argv[1] 
p2= sys.argv[2] 
p3= sys.argv[3] 
p4= sys.argv[4] 
p5= sys.argv[5] 
p6= sys.argv[6]
p7= sys.argv[7]
p8= sys.argv[8]
p9= sys.argv[9]


# List of strings
row_contents = [p1,p2,p3,p4,p5,p6,p7,p8,p9]
 
# Append a list as new line to an old csv file
append_list_as_row('flightdataset2.csv', row_contents)
print(1)