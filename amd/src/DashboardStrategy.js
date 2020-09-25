/**
 * DashboardCompletion
 *
 * @module     format/ladtopics
 * @class      DashboardCompletion
 * @copyright  2020 Niels Seidel <niels.seidel@fernuni-hagen.de>
 * @description xxx
 * @license    MIT
 * @since      3.1
 * 
 * @todo
 * - display repetition of activities
 * - provide additional information for each activity using a popover or tooltip
 * - fix empty section names
 */

define([
    'jquery',
    M.cfg.wwwroot + "/course/format/ladtopics/lib/build/vue.min.js"
], function ($, Vue) {

    return Vue.component('dashboard-completion',
        {
            props: ['course', 'milestones'],

            data: function () {
                return {
                    currentStrategy: null,
                    currentMenuItem: 'cognitive',
                    the_milestones: [],
                    strategyCategories: [
                        /*{
                            id: 'cognitive',
                            name: 'Lernen gestalten',
                            desc: 'Bei kognitiven Lernstrategien unterscheidet man zwischen Organisations-, Elaborations- und Wiederholungsstrategien. Organisationsstrategien betrachten, wie der/die Lernernde sein/ihr Wissen organisiert und für den weiteren Lernprozess strukturiert. Strategien, die dies konkret veranschaulichen, sind das Erstellen von Mindmaps, das Verfassen von Exzerpten oder Gliederungen zum Lernstoff sowie das Sammeln wichtiger Inhalte, z.B. durch das Erstellen von Tabellen, Diagrammen, Schaubildern oder Listen mit Fachausdrücken und Definitionen. Elaborationsstrategien werden eingesetzt, um ein erweitertes Wissen zu generieren. Lernende bedienen sich dabei meist der bereits internalisierten Schemata und Wissensbasen und nutzen z. B. vertraute Abläufe, um Querbezüge herzustellen. vgl. Wissenssynthese. Wiederholungsstrategien sind notwendig, um sich Lernstoff dauerhaft einzuprägen und gleichsam eine schnelle Verfügbarkeit von Wissen zu gewährleisten. Daher stehen hier Lernaktivitäten wie z. B. Auswendiglernen mit Lernkarten und repetierende Übungen im Vordergrund.',
                        },*/
                        {
                            id: 'organisation',
                            name: 'Lernstoff organisieren',
                            desc: 'Organisationsstrategien betrachten, wie der/die Lernernde sein/ihr Wissen organisiert und für den weiteren Lernprozess strukturiert. Strategien, die dies konkret veranschaulichen, sind das Erstellen von Mindmaps, das Verfassen von Exzerpten oder Gliederungen zum Lernstoff sowie das Sammeln wichtiger Inhalte, z.B. durch das Erstellen von Tabellen, Diagrammen, Schaubildern oder Listen mit Fachausdrücken und Definitionen.',
                        },
                        {
                            id: 'elaboration',
                            name: 'Inhalte erarbeiten',
                            desc: 'Elaborationsstrategien werden eingesetzt, um ein erweitertes Wissen zu generieren. Lernende bedienen sich dabei meist der bereits internalisierten Schemata und Wissensbasen und nutzen z. B. vertraute Abläufe, um Querbezüge herzustellen. vgl. Wissenssynthese.',
                        },
                        {
                            id: 'build',
                            name: 'Wissen aufbauen',
                            desc: 'Wiederholungsstrategien sind notwendig, um sich Lernstoff dauerhaft einzuprägen und gleichsam eine schnelle Verfügbarkeit von Wissen zu gewährleisten. Daher stehen hier Lernaktivitäten wie z. B. Auswendiglernen mit Lernkarten und repetierende Übungen im Vordergrund.',
                        },
                        {
                            id: 'metacognitive',
                            name: 'Lernprozess verbessern',
                            desc: 'Bei den metakognitiven Lernstrategie geht es darum den Lernprozess zu Steuern und zu kontrollieren. Dazu bedient man sich Strategien wie dem Planen, Vorbereiten und Ziele setzen. Dies geht eiher mit einem stetigen Prozess der Selbsteinschätzung und Selbstregulation. Nur so kann der Lernprozess stetig verbessert und angepasst werden. '
                        },
                        {
                            id: 'resource',
                            name: 'Ressourcen nutzen',
                            desc: 'Ressourcenorientierte Selbstorganisation im StudiumUm eine Balance zwischen Studium und beruflichen wie privaten Verpflichtungen herzustellen, ist es wichtig, die eigenen Ressourcen zu kennen und sich Zeit und Energie im Studium gut einzuteilen.Eine systematische Auseinandersetzung mit Zielen, Anstrengungen beim Lernen und der eigenen Aufmerksamkeitsfähigkeit soll Ihnen helfen, Ihre Ressourcen besser kennen zu lernen.Motivation und Durchhaltevermögen können Sie durch eine Balance zwischen Selbstverpflichtung, Belohnung und Regeneration steigern. '
                        }
                    ],

                    strategies: [
                        // cognitive strategies
                        /*{
                            id: 'organisation', subheading: true, name: 'Organisationsstrategien', category: 'cognitive'
                        },*/
                        { 
                            id: 'reading', name: 'Überblick durch Querlesen', desc: '', category: 'organisation' },
                        {
                            id: 'mindmap', name: 'Mindmap', desc: 'Mit Mindmaps können Sie zentrale Themen, Bezüge und Zusammengänge grafisch darstellen und visuell veranschaulichen. Bei einer Mindmap wird die zentrale Idee eines Textes bzw. der zentrale Begriff, in der Mitte des Blattes platziert. Weitere Schlüsselwörter, die im Text behandelt werden, werden nun in Relation dazu (Abstand - Nähe; Schriftgröße, etc.) hinzugefügt. Sie können diese Begriffe auch durch Symbole oder Kurzkommentare ergänzen. Es empfiehlt sich allerdings, nicht ganze Sätze zu formulieren, da die Übersichtlichkeit darunter leiden könnte.', category: 'organisation'
                        },
                        {
                            id: 'exzerpt', name: 'Exzerpte / Zusammenfassungen', desc: 'Ein Exzerpt ist mehr als nur eine einfache Zusammenfassung der wichtigsten Inhalte. Gerade wenn es darum geht, vor einer Prüfung Lerninhalte noch einmal zu wiederholen, helfen Ihnen Exzerpte oder Zusammenfassungen dabei, schnell in die einzelnen Wissensbereiche einzutauchen, ohne auf das ausführliche Kursmaterial zurückzugreifen. Zudem können Sie schon bei der Erstellung eines Exzerpts üben, Wissen in eigenen Worten wiederzugeben. Auch kritische Perspektiven und eine wissenschaftliche Schreibweise sollten Sie in einem Exzerpt berücksichtigen.', category: 'organisation'
                        },
                        {
                            id: 'toc', name: 'Gliederungen', desc: 'Gliederungen helfen, einen Überblick über den zu lernenden Inhalt zu bekommen. Wissen kann so leichter strukturiert oder kategorisiert werden. Themenfelder lassen sich mit einer Gliederung z. B. übersichtlich strukturieren.', category: 'organisation'
                        },
                        {
                            id: 'structure', name: 'Strukturierung von Wissen', desc: 'Um den Lernstoff klarer darzustellen, ist die Erstellung von Tabellen, Diagrammen, Listen oder Schaubildern hilfreich. Fachausdrücke oder Definitionen lassen sich gut in Listen oder Tabellen sammeln.', category: 'organisation'
                        },
                        {
                            id: 'cards', name: 'Lernkarten früh erstellen', desc: 'Lernkarten können schon sehr früh digital z. B. in einer App oder auf Papier erstellt werden und die Lernorganisation so erleichtern. Dabei können nicht nur Begriffe notiert werden, sondern auch Prozesse oder mögliche Fragestellungen, die Sie z. B. in der Prüfung erwarten könnten.', category: 'organisation'
                        },
                        
                        
                        /*{
                            id: 'elaboration', subheading: true, name: 'Elaborationsstrategien', category:'cognitive'
                        },*/
                        {
                            id: 'fastread', name: 'schnelles Lesen', desc: 'Üben Sie das schnelle Lesen, indem Sie einmal probieren, so schnell zu lesen, wie Sie können. Lesen Sie so schnell, dass Sie kaum etwas vom Inhalt des Textes mitbekommen. Betrachten Sie das als eine Tempo-Übung. Eine weitere Übung, um die Lesegeschwindigkeit zu erhöhen, ist die Vergrößerung des Fixierungsbereichs; lesen Sie in Wortgruppen anstelle des wortwörtlichen Lesens.Beide Prozesse werden durch die nachfolgenden Abbildungen dargestellt. :>> Abb normale_Lesebewegung.png und schnelle_Lesebewegung.png >> Eine weitere unterstützende Technik bietet die Beschleunigung des Lesefingers.Lesen Sie zu Beginn mit dem Finger unter den Zeilen.Das schult die Blickbewegung, so dass mehrere Worte auf einmal wahrgenommen werden können.Steigern Sie dabei das Tempo Ihres Fingers; je schneller der Finger über die Zeilen gleitet, desto schneller müssen Sie auch lesen. ', category: 'elaboration'
                        },
                        {
                            id: 'readingcomprehension', name: 'Leseverständnis steigern', desc: '', category: 'elaboration'
                        },
                        {
                            id: 'transfertoknown', name: 'Übertragung auf bekannte Schemata', desc: '', category: 'elaboration' 
                        },
                        {
                            id: 'critical', name: 'kritisches Hinterfragen', desc: '', category: 'elaboration' 
                        },
                        {
                            id: 'subjectrelations', name: 'Bezug zu anderen Fächern herstellen', desc: '', category: 'elaboration'
                        },
                        {
                            id: 'PQ4R', name: 'PQ4R-Methode', desc: '', category: 'elaboration'
                        },
                        
                        
                        /*{
                            id: 'rep', name: 'Wiederholungsstrategien', subheading:true, category: 'cognitive'
                        },*/
                        {
                            id: 'cards', name: 'Lernkartei', desc: 'Mit einer Lernkartei können Sie Dinge systematisch wiederholen. Eine Karte wandert bei einer richtigen Antwort ein Fach weiter, bei einer falschen Antwort bleibt die Karte im Fach. Das 1. Fach wird z. B. täglich wiederholt, das 2. Fach alle 3 Tage usw. So arbeiten Sie sich durch Ihre Lernkartei, bis alles für die Prüfung sitzt.', category: 'build'
                        },
                        {
                            id: 'repetieren', name: 'Repetieren', desc: '', category: 'build'
                        },
                        {
                            id: 'reminder', name: 'Kleine Erinnerungshilfen', desc: 'Mit einem Reim oder einer Eselsbrücke können Sie sich Begriffe oder Reihenfolgen einfacher merken.', category: 'build'
                        },
                        {
                            id: 'remindercomplex', name: 'Erinnerungshilfen für komplexe Inhalte', desc: 'Die Loci-Methode oder auch der Lernspaziergang sind Methoden, um sich Dinge in einer konkreten Reihenfolge einzuprägen. Mit der Loci Methode können Sie sich komplexe Dinge wie z. B. Prozesse oder Stufenmodelle schneller merken, indem Sie Lerninhalte mit Gegenständen oder Orten und Abfolgen aus dem Alltag verknüpfen. Diese Methode ist auch als Gedächtnisspaziergang bekannt.', category: 'build'
                        },

                        //// metacognitive


                        {
                            id: 'planning', name: 'Planen', desc: 'Bei der Planung kommt es darauf an Anforderungen zu analysieren, Lernziele zu formulieren und passende Lernstrategien für die Umsetzung auszuwählen. Der Meilensteinplaner in dieser Lernumgebung soll Sie dabei unterstützen. In einem Meilenstein können Sie ein Lernziel und die Anforderungen dazu festhalten. Über das Menü können Sie dann Aktivitäten und Materialien aus der Lernumgebung auswählen oder individuelle Arbeitsmaterialien vermerken.', category: 'metacognitive'
                        },
                        {
                            id: 'prepare', name: 'Vorbereiten', desc: 'Was gehört zu einer guten Vorbereitung? Zunächst einmal sollten Sie sich ein anregendes Arbeitsumfeld schaffen und dafür einen geeigneten Ort wählen.Dieser Ort wird in den nächsten Wochen und Monaten Ihr ganz persönlicher Arbeitsplatz sein.Viele Studierende können besser lernen, wenn sie dafür einen Ort auswählen, den sie mögen und der für sie positiv besetzt ist.Dies kann beispielsweise die Bibliothek in der Nähe sein oder ein ruhiges Arbeitszimmer, in dem man schon andere Dinge bearbeitet hat, die zum Erfolg führten.Ihr Arbeitsbereich sollte möglichst störungs- und ablenkungsfrei sein und Sie sollten die notwendige Ausstattung eingerichtet oder griffbereit haben.Erzwungene Lernpausen, weil man erst den Stift suchen muss oder der Textmarker fehlt, sind ärgerlich und unnötig.Überlegen Sie also, welche Materialien Sie für Ihren Arbeitsprozess brauchen und legen Sie sich diese vorab zurecht.Sorgen Sie dafür, dass Ihr elektronisches Endgerät auf dem neuesten Stand ist und eine genügend große Bandbreite für die Datenübertragung zur Verfügung steht.Dann kann es ja weiter gehen.', category: 'metacognitive'
                        },
                        {
                            id: 'selfconfidence', name: 'Selbsteinschätzung', desc: 'Überprüfen Sie immer wieder Ihr Verständnis der Kursinhalte. Nutzen Sie dazu Quiz, Übungsaufgaben und Self-Assessments, die Ihnen ein Feedback zu Ihrem Lernstand geben. Ihr Ziel sollte es sein, sich immer besser selbst einschätzen zu können. Die Ansichten zum Lernfortschritt und die Quiz-Übersicht hilft ihnen dabei wahrzunehmen, wie groß Ihr Fortschritt ist.', category: 'metacognitive'
                        },
                        {
                            id: 'regulations', name: 'Regulationsstrategien', desc: 'Regulationsstrategien dienen in der Regel der Identifikation von Verständnislücken und tragen zum Ergreifen von Maßnahmen zur Schließung der Lücken bei. Es gibt verschiedene Ansätze, um Ihr Lernen zu regulieren. Dabei gestaltet sich dieser Prozess in der Regel sehr individuell für eine*n Lernenden. Man kann nicht mit Bestimmtheit sagen, dass nur bestimmte Lernstrategien direkte positive Wirkungen auf den Studienerfolg haben. Vielmehr gibt es auch die Überlegung, dass Sie je nach Lerntyp mit unterschiedlichen Lernstrategien zum Studienerfolg kommen können. Insofern erscheint es erforderlich, den Strategieeinsatz zu planen, zu reflektieren und ggf. anzupassen. Überprüfen Sie, welche Prüfungsanforderungen gegeben sind (z. B. reines Faktenwissen auswendig lernen oder Zusammenhänge erkennen, Wissen auf neue Anwendungsgebiete zu transferieren), auf welche Ressourcen Sie zurückgreifen können (z. B. gemeinsames Lernen mit Kommilitonen*innen) und planen Sie die Zeit für den Lernaufwand bzw. die konkrete Prüfungsvorbereitung ein. Reflektieren Sie außerdem, welche Lernerfahrungen Sie bisher gemacht haben und welche Lernstile und -strategien Sie bevorzugen. Überlegen Sie, wie und in welchen Situationen Sie am besten lernen. Welche Lernstrategien Sie nutzen, hängt nicht zuletzt auch davon ab, welche Lernerfahrungen Sie machen. Wenn Sie merken, dass Sie besonders gut gemeinsam mit anderen Kommilitonen/-innen lernen können, werden Sie das kooperative Lernen als Lernstrategie womöglich im weiteren Verlauf Ihres Studiums beibehalten. Diese Lernumgebung unterstützt Sie, indem zum Abschluss jedes Meilensteins eine Reflexion angeboten wird. Die Erkenntnisse, die Sie sich in diesem Reflexionsprozess im Freitextfeld notieren werden für Sie hier im Bereich Lernstrategien für Sie festgehalten. So können Sie die Entwicklung Ihrer Lernstrategien selbst beobachten und nachvollziehen.', category: 'metacognitive'
                        },
                        {
                            id: 'goals', name: 'Ziele setzen', desc: 'Informationen zu den Prüfungsanforderungen finden Sie in der Regel im Modulhandbuch bzw. im Studienportal und in der Moodle-Lernumgebung des jeweiligen Moduls. Große Lernziele stellen beispielsweise das Bestehen einer Prüfung oder ein zügiger Fortschritt im Studium dar.Die Arbeit an diesen Zielen erstreckt sich zudem über einen recht langen Zeitraum, der von Ihnen vor allem Disziplin und Durchhaltevermögen verlangt.Um Ihr großes Ziel zu erreichen, macht es daher Sinn, dieses in kleinere - schneller zu erreichende - Ziele aufzuteilen. Kleinere Lernziele können zum einen die Lerninhalte sein, die Sie für ein Modul erarbeiten.Hierzu bieten einige Module eine zeitliche Strukturierung oder einen Lesekurs an, mit denen Sie dann systematisch durch das Studienmaterial geleitet werden.Zusätzlich können Sie sich auch ganz individuelle Ziele setzen.Kleinere Ziele können zum Beispiel wie folgt aussehen:<ul><li>ein Kapitel im Studienbrief lesen und zusammenfassen</li><li>Lernkarten zu einem Themenbereich erstellen</li><li>eine bestimmte Anzahl an Übungsaufgaben lösen</li><li>eine Seite für die Hausarbeit verfassen</li></ul>Setzen Sie zu Beginn eines jeden Semestern Ihre großen und kleinen Lernziele fest und überlegen Sie sich eine möglichst realistische Zeitvorgabe, um Ihre Ziele zu erreichen.Lassen Sie dabei Ihren Gesamtzeitplan für Ihr Studium aber nicht außer Acht.', category: 'metacognitive'
                        },

                        //// resource
                        {
                            id: 'anstr', name: 'Anstrengungsmanagement', desc: '', category: 'resource'
                        },
                        {
                            id: 'attention', name: 'Aufmerksamkeitsmanagement', desc: '', category: 'resource'
                        },
                        {
                            id: 'resour', name: 'Ressourcenmanagement', desc: '', category: 'resource'
                        },
                        {
                            id: 'time', name: 'Zeitmanagement', desc: '', category: 'resource'
                        },
                        {
                            id: 'efftime', name: 'Effektives Zeitmanagement', desc: '', category: 'resource'
                        },
                        {
                            id: 'partner', name: 'Lernpartner', desc: 'Nutzen Sie den Austausch mit Kommilitonen*innen, um Lernstoff zu diskutieren, um sich gegenseitig Lerninhalte zu erklären, oder auf spielerische Weise ein Speed-Quiz zu machen. Oft klärt ein Nachfragen bei einem Kommilitonen offene Fragen schneller, wenn man selbst die Antwort nicht auf Anhieb finden kann. Lernpartner können zudem dabei helfen ein Motivationstief zu überwinden.', category: 'resource'
                        },
                        {
                            id: 'self', name: 'Selbstverpflichtung', desc: 'Gerade das Fernstudium verlangt von Ihnen eigenständiges Arbeiten und Selbstorganisation. Nehmen Sie sich also selbst in die Pflicht und setzen Sie sich Teilziele, die systematisch erarbeitet werden können. Dafür ist ein realistischer Zeitplan, in dem Sie regelmäßige und feste Lernzeiten verbindlich festlegen, aber auch nötige Pausen, Ferien und Entspannungszeiten berücksichtigen, sehr hilfreich.', category: 'resource'
                        },
                        {
                            id: 'literature', name: 'Literatur', desc: 'Weiterführende Literatur ist im Studium eine wichtige Ressource. Schlagen Sie unbekannte Fachbegriffe nach, oder schließen Sie Verständnislücken, indem Sie zu weiterführender Literatur greifen, die Ihnen den Sachverhalt z. B. aus einer anderen Perspektive veranschaulicht. Nutzen Sie dazu auch das Angebot der Universitätsbibliothek in Hagen oder das einer Bibliothek in der Nähe Ihres Wohnortes.', category: 'resource'
                        },
                        {
                            id: 'thefts', name: 'Zeitdiebe', desc: '', category: 'resource'
                        }

                    ],
                    info: '',

                };
            },

            mounted: function () {
                $(document).ready(function () {
                    $('#cognitive').tab('show');
                });
            },

            created: function () {
                $(function () {
                    $('#strategyTab li:first-child a').tab('show');
                });
            },

            methods: {
                strategiesByCategory: function (cat) {
                    return this.strategies.filter(function (s) {
                        return s.category === cat ? true : false;
                    });
                },
                strategyById: function (id) {
                    return this.strategies.filter(function (s) {
                        return s.id === id ? s : false;
                    })[0];
                },
                getSelectedStrategy: function () {
                    return this.currentStrategy !== null ?
                        this.strategyById(this.currentStrategy) : { name: '', desc: '' };
                },
                getReflections: function (milestones) {
                    return this.milestones.filter(function (m) {
                        return m.reflections[3].length > 0 ? true : false;
                    });
                },
                getMilestones: function(){
                    return this.milestones;
                }
            },

            watch: {
                milestones: function (m) {
                    console.log('ee', this.getReflections(m), m)
                    this.the_milestones = m;//this.getReflections(m);
                }
            },

            template: `
            <div>
                <h4>Lernen gestalten</h4>
                <div class="row">
                    <div class="col-3">
                        <ul class="nav flex-column flex-nowrap overflow-hidden">
                            <li v-for="pc in strategyCategories" class="nav-item">
                                <a :class="currentMenuItem==pc.id ? 'nav-link text-truncate mb-0 pb-0' : 'nav-link collapsed text-truncate'" v-on:click="currentMenuItem=pc.id" :href="'#submenu-'+pc.id" data-toggle="collapse" :data-target="'#submenu-'+pc.id" style="cursor:pointer;">
                                    <i :class="currentMenuItem==pc.id ? 'fa fa-caret-down' : 'fa fa-caret-right'"></i> 
                                    <span class="d-none d-sm-inline bold">{{ pc.name}}</span>
                                </a>
                                <div v-if="strategiesByCategory(pc.id).length > 0" :class="currentMenuItem==pc.id ? 'collapse fade show' : 'collapse fade'" :id="'submenu-'+pc.id" aria-expanded="false">
                                    <ul class="flex-column pl-2 nav">
                                        <li v-for="s in strategiesByCategory(pc.id)" :style="currentStrategy==s.id ? 'background-color:lightblue;' : ''" :class="currentStrategy == s.id ? 'nav-item active' : 'nav-item'">
                                            <a v-if="s.subheading !== true" class="bl-2 pl-1 ml-4 nav-link py-0" v-on:click.prevent="currentStrategy=s.id" style="cursor:pointer;">
                                                <span>{{s.name}}</span>
                                            </a>
                                            <span v-if="s.subheading" class="pl-1 ml-2">{{s.name}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div id="strategy-description" class="col-6">
                        <div class="bold">{{ getSelectedStrategy().name }}</div>
                        <div>{{ getSelectedStrategy().desc }}</div>
                    </div>
                    <div class="col-3 border-left">
                        <div class="bold mb-3">Meine Notizen aus der Reflexion</div>
                        <div v-for="r in milestones">
                            <span v-if="r.reflections[3]" class="card p-1 mb-2" style="font-size:0.8em">{{ r.reflections[3] }}</span>
                        </div>
                    </div>
                </div>
            `
        });
});