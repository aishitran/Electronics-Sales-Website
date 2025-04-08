import os

def create_project_md(root_dir, output_file="project_content.md"):
    with open(output_file, 'w', encoding='utf-8') as outfile:
        # Walk through directory tree
        for dirpath, dirnames, filenames in os.walk(root_dir):
            for filename in filenames:
                file_path = os.path.join(dirpath, filename)
                # Skip the output file itself
                if file_path == output_file:
                    continue
                
                # Write file path in brackets
                outfile.write(f"[{file_path}]\n")
                
                # Write file content
                try:
                    with open(file_path, 'r', encoding='utf-8') as infile:
                        content = infile.read()
                        outfile.write(content + "\n\n")
                except Exception as e:
                    outfile.write(f"Error reading file: {str(e)}\n\n")

    print(f"Project content has been written to {output_file}")

# Run the function from the current directory
create_project_md('.')