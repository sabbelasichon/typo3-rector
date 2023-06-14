<v:variable.set name="myvariable" value="a string value" />
{myvariable -> v:variable.set(name:'othervariable')}
-----
<f:variable name="myvariable" value="a string value" />
{myvariable -> f:variable(name:'othervariable')}
