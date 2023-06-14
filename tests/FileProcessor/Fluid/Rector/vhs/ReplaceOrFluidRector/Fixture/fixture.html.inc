{someVariable -> v:or(alternative:'Fallback text')}
{v:or(content: someVariable, alternative: 'Fallback text')}
-----
{someVariable ?: 'Fallback text'}
{someVariable ?: 'Fallback text'}
