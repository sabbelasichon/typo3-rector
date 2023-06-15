Simple (and easily migratable) cases:
{myvariable -> v:variable.set(name:'othervariable')}
<v:variable.set name="myvariable" value="a string value" />

More complex cases (and unmigratable) cases:
<v:variable.set name="myarray.property" value="a string value" />
<v:variable.set value="a string value" name="myvariable" />
-----
Simple (and easily migratable) cases:
{myvariable -> f:variable(name:'othervariable')}
<f:variable name="myvariable" value="a string value" />

More complex cases (and unmigratable) cases:
<v:variable.set name="myarray.property" value="a string value" />
<v:variable.set value="a string value" name="myvariable" />
