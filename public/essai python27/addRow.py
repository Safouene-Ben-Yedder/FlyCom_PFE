from csv import writer
 
def append_list_as_row(file_name, list_of_elem):
    # Open file in append mode
    with open(file_name, 'a', newline='\n') as write_obj:
        # Create a writer object from csv module
        csv_writer = writer(write_obj)
        # Add contents of list as last row in the csv file
        csv_writer.writerow(list_of_elem)


# List of strings
row_contents = [1,227,228,34,45,56,0,0,1]
 
# Append a list as new line to an old csv file
append_list_as_row('flightdataset2.csv', row_contents)


