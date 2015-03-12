\documentclass{tufte-book}
\usepackage{lipsum}

\newenvironment{loggentry}[2]% date, heading
{\noindent\textbf{#2}\marginnote{#1}\\}{\vspace{0.5cm}}

\begin{document}


\begin{loggentry}{2009-Oct-31}{Snow}
    \lipsum[1]
\end{loggentry}

\begin{loggentry}{2010-Dez-31}{Water of Life}
    \lipsum[2]
\end{loggentry}

\begin{loggentry}{2011-Nov-15}{Cold}
    \lipsum[3-5]
\end{loggentry}

\begin{loggentry}{2012-Aug-24}{Sunrise}
    \lipsum[6-7]
\end{loggentry}

\end{document}